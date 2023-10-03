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

    function find(Request $request): JsonResponse {

        $validator = Validator::make($request->all(), [
            'category_id' => ['required', 'exists:categories,id']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $category = Category::find($request->input('category_id'));

        return $this->respondWithSuccess($category);
    }

    function update(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'name' => ['max:255'],
            'category_id' => ['required', 'exists:categories,id']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $category = Category::find($request->input('category_id'));

        if ($request->filled('category_id')) {
            $category->update(['name' => $request->get('name')]);
        }

        return $this->respondWithSuccess();
    }

    function delete(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'category_id' => ['required', 'exists:categories,id']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $category = Category::find($request->input('category_id'));

        $category->delete();

        return $this->respondWithSuccess();
    }
}
