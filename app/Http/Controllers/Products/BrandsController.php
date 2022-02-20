<?php

namespace App\Http\Controllers\Products;

use App\Data\Responses\Products\BrandResponse;
use App\Http\Controllers\Controller;
use App\Models\Products\Brand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandsController extends Controller
{
    public function getBrands(Request $request): JsonResponse
    {
        $categories = Brand::all();
        return BrandResponse::jsonSerialize($categories, $request->page);
    }

    public function createBrand(Request $request): JsonResponse
    {
        $validator =\Validator::make($request->all(), Brand::rules());
        if($validator->fails()) {
            return response()->json( $validator->errors(), 400);
        }
        $category = Brand::create($request->all());
        return response()->json((new BrandResponse($category))->toArray());
    }

    public function updateBrand(Request $request, string $uuid): JsonResponse
    {
        try{
            $this->validate($request, Brand::rules());
        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);
        }
        $category = Brand::whereUuid($uuid)->firstOrFail();
        $category->update($request->all());
        return response()->json((new BrandResponse($category))->toArray());
    }

    public function getBrand(string $uuid): JsonResponse
    {
        $category = Brand::whereUuid($uuid)->firstOrFail();
        return response()->json((new BrandResponse($category))->toArray());
    }

    public function deleteBrand(string $uuid): JsonResponse
    {
        $category = Brand::whereUuid($uuid)->firstOrFail();
        $category->delete();
        return response()->json(['message' => 'Brand deleted']);
    }
}
