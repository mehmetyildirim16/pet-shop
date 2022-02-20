<?php

namespace App\Http\Controllers\Products;

use App\Data\Responses\Products\CategoryResponse;
use App\Http\Controllers\Controller;
use App\Models\Products\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function getCategories(Request $request): JsonResponse
    {
        $categories = Category::all();
        return CategoryResponse::jsonSerialize($categories, $request->page);
    }

    public function createCategory(Request $request): JsonResponse
    {
        $validator =\Validator::make($request->all(), Category::rules());
        if($validator->fails()) {
            return response()->json( $validator->errors(), 400);
        }
        $category = Category::create($request->all());
        return response()->json((new CategoryResponse($category))->toArray());
    }

    public function updateCategory(Request $request, string $uuid): JsonResponse
    {
        try{
            $this->validate($request, Category::rules());
        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
        $category = Category::whereUuid($uuid)->firstOrFail();
        $category->update($request->all());
        return response()->json((new CategoryResponse($category))->toArray());
    }

    public function getCategory(string $uuid): JsonResponse
    {
        $category = Category::whereUuid($uuid)->firstOrFail();
        return response()->json((new CategoryResponse($category))->toArray());
    }

    public function deleteCategory(string $uuid): JsonResponse
    {
        $category = Category::whereUuid($uuid)->firstOrFail();
        $category->delete();
        return response()->json(['message' => 'Category deleted']);
    }
}
