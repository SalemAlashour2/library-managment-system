<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Books;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    function create(Request $request) : JsonResponse {

        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'exists:users,id'],
            'book_id' => ['required','exists:books,id'],
            'return_date' => ['date']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $booking = Booking::create([
            'book_id' => $request->input('book_id'),
            'user_id' => $request->input('user_id'),
            'return_date' => $request->input('return_date')
        ]);

        $user = User::findOrFail($request->input('user_id'));
        $book = Books::findOrFail($request->input('book_id'));

        $user->bookings()->save($booking);
        $book->bookings()->save($booking);

        return $this->respondCreated();

    }

    function read() : JsonResponse {
        
        $bookings = Booking::all();

        return $this->respondWithSuccess($bookings);
    }

    function find(Request $request) : JsonResponse {

        $validator = Validator::make($request->all(), [
            'booking_id' => ['required', 'exists:bookings,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        $booking = Booking::findOrFail($request->input('booking_id'));

        return $this->respondWithSuccess($booking);
    }

    function update(Request $request) : JsonResponse {

        $validator = Validator::make($request->all(), [
            'user_id' => ['exists:users,id'],
            'book_id' => ['exists:books,id'],
            'return_date' => ['date'],
            'booking_id' => ['required','exists:bookings,id']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $booking = Booking::find($request->input('booking_id'));

        if($request->filled('user_id')){

            $user = User::find($request->input('user_id'));
            $user->bookings()->save($booking);
        }

        if ($request->filled('book_id')) {

            $book = Books::find($request->input('book_id'));
            $book->bookings()->save($booking);
        }

        if($request->filled('return_date')){
            $booking['return_date'] = $request->input('return_date');
        }

        return $this->respondWithSuccess();
    }

    function delete(Request $request) : JsonResponse {

        $validator = Validator::make($request->all(), [
            'booking_id' => ['required','exists:bookings,id']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $booking = Booking::find($request->input('booking_id'));

        $booking->delete();

        return $this->respondWithSuccess();

    }
}
