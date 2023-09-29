<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class CategoryController extends Controller
{

    function create(Request $request) : JsonResponse {
        
        $validator = Validator::make($request->all(),[
            'name' => ['required','max:255']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ],Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        $category = Category::create([
            'name' => $request['name']
        ]);
        $category->save();

        return $this->respondCreated();
    }

    function read() : JsonResponse {

        $category = Category::all();

        return $this->respondWithSuccess($category);
    }
}
