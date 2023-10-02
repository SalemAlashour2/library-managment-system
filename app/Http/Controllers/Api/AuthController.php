<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse{

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (Auth::attempt($request->all())) {
            $token = $request->user()->createToken('user token',['user']);

            return $this->respondWithSuccess([
                'token' => $token->plainTextToken
            ]);
        }

        return $this->respondUnAuthenticated();
    }

    function register(Request $request) : JsonResponse {

        $validator = Validator::make($request->all(), [
            'username' => 'required|max:255',
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'confirmPassword' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::create([
            'username' => $request['username'],
            'first_name' => $request['firstname'],
            'last_name' => $request['lastname'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        $role = Role::where('name', 'user')->first();
        $user->roles()->attach($role);

        $user->save();
        $token = $user->createToken('user token');

        return $this->respondCreated([
            'token' => $token->plainTextToken
        ]);
    }

    function loginAdmin(Request $request) : JsonResponse {

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (Auth::attempt($request->all())) {

            if ($request->user->hasRole('admin')) {
                $token = $request->user->createToken('admin-token', [
                    'category-create', 'category-update', 'category-delete', 
                    'author-create', 'author-update', 'author-delete', 'book-create', 'book-update', 'book-delete'
                ]);
                return $this->respondWithSuccess([
                    'token' => $token->plainTextToken
                ]);           
             }

            return $this->respondUnAuthenticated('User is not admin');
        }

        return $this->respondUnAuthenticated('User not found');
    }

    function registerAdmin(Request $request) : JsonResponse {
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:255',
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'confirmPassword' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::create([
            'username' => $request['username'],
            'first_name' => $request['firstname'],
            'last_name' => $request['lastname'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        $role = Role::where('name', 'admin')->first();
        $user->roles()->attach($role);

        $user->save();
        $token = $user->createToken('admin-token',['category-create','category-update','category-delete'
        ,'author-create','author-update','author-delete','book-create','book-update','book-delete']);

        return $this->respondCreated([
            'token' => $token->plainTextToken
        ]);
    }
    
}
