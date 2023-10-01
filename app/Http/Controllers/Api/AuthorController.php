<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthorController extends Controller
{
    function create(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $author = Author::create([
            'name' => $request['name']
        ]);
        $author->save();

        return $this->respondCreated();
    }

    function read(): JsonResponse
    {

        $authors = Author::all();

        return $this->respondWithSuccess($authors);
    }

    function find(Request $request) : JsonResponse {

        $validator = Validator::make($request->all(), [
            'author_id' => ['required', 'exists:authors,id']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        $author = Author::find($request->input('author_id'));

        return $this->respondWithSuccess($author);
    }

    function update(Request $request) : JsonResponse {

        $validator = Validator::make($request->all(), [
            'name' => ['max:255'],
            'author_id' => ['required','exists:authors,id']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $author = Author::find($request->input('author_id'));

        if($request->filled('author_id')){
            $author->update(['name'=>$request->get('name')]);
        }

        return $this->respondWithSuccess();
        
    }
}
