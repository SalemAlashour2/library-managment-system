<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Books;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    function create(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'author_id' => ['exists:authors,id'],
            'category_id' => ['exists:categories,id'],
            'image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Added image validation

        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        $book = Books::create(['name' => $request->input('name')]);


        if ($request->filled('author_id')) {
            $author = Author::find($request->input('author_id'));
            $author->books()->save($book);
        }

        if ($request->filled('category_id')) {
            $category = Category::find($request->input('category_id'));
            $category->books()->save($book);
        }

        if ($request->hasFile('image')) {
            $imagePath = $this->uploadImage($request);
            $book->image = $imagePath;
            $book->save();
        }

        return $this->respondCreated();
    }


    function read(): JsonResponse
    {

        $books = Books::all();

        return $this->respondWithSuccess($books);
    }

    function find(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'book_id' => ['required', 'exists:books,id']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $book = Books::find($request->input('book_id'));

        return $this->respondWithSuccess($book);
    }

    function update(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'name' => ['max:255'],
            'book_id' => ['required','exists:books,id'],
            'author_id' => ['exists:authors,id'],
            'category_id' => ['exists:categories,id'],
            'image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $book = books::find($request->input('book_id'));

        if($request->filled('name')){
            $book->update(['name' => $request->get('name')]);
        }

        if ($request->filled('author_id')) {
            $author = Author::find($request->input('author_id'));
            $author->books()->save($book);
        }

        if ($request->filled('category_id')) {
            $category = Category::find($request->input('category_id'));
            $category->books()->save($book);
        }

        if($request->hasFile('image')){
            $imagePath = $this->uploadImage($request);
            if($imagePath != null){
                Storage::delete($book['image']);
                $book->update(['image' => $imagePath]);
                $book->save();
            }

        }



        return $this->respondWithSuccess();
    }

    function uploadImageRequest(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'book_id' => ['exists:books,id'],
            'image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Added image validation

        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $book = Books::find($request->input('book_id'));

        $book['image'] = $this->uploadImage($request);

        return $this->respondCreated();
    }



    private function uploadImage($request): string
    {

        $path = Storage::putFile('books', $request->file('image'));

        return $path;
    }
}
