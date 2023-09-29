<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;


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

        $author = Author::all();

        return $this->respondWithSuccess($author);
    }
}
