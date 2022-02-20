<?php

namespace App\Http\Controllers\Products;

use App\Actions\ProductAction;
use App\Data\Responses\Products\ProductResponse;
use App\Http\Controllers\Controller;
use App\Models\Products\Brand;
use App\Models\Products\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function __construct(private ProductAction $action) { }

    public function getProducts(Request $request): JsonResponse
    {
        $products = Product::all();
        return ProductResponse::jsonSerialize($products, $request->page);
    }

    public function getProduct(string $uuid): JsonResponse
    {
        $product = Product::whereUuid($uuid)->firstOrFail();
        return response()->json((new ProductResponse($product))->toArray());
    }

    public function createProduct(Request $request): JsonResponse
    {
        $validator =\Validator::make($request->all(), Product::rules());
        if($validator->fails()) {
            return response()->json( $validator->errors(), 400);
        }
        $product = $this->action->create($request->all());
        return response()->json((new ProductResponse($product))->toArray());
    }

    public function updateProduct(Request $request, string $uuid): JsonResponse
    {
        $product = $this->action->update($uuid, $request->all());
        return response()->json((new ProductResponse($product))->toArray());
    }

    public function deleteProduct(string $uuid): JsonResponse
    {
        $product = Product::whereUuid($uuid)->firstOrFail();
        $product->delete();
        return response()->json('Product deleted');
    }

}
