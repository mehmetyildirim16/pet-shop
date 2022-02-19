<?php

namespace App\Actions;

use App\Models\Products\Product;

class ProductAction
{
    public function create(array $data): Product
    {
        $product = Product::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'price' => $data['price'],
            'metadata' => [['brand' => $data['brand']]],
            'category_uuid' => $data['category_uuid'],
                        ]);
        if(isset($data['image'])) {
            $product->addFile($data['image']);
        }
        return $product;
    }

    public function update(string $uuid, array $all): Product
    {
        $product = Product::whereUuid($uuid)->firstOrFail();
        if(isset($all['brand'])) {
            $brand = $all['brand'];
            unset($all['brand']);
            $product->metadata = [['brand' => $brand]];
        }
        if(isset($all['image'])) {
            $image = $all['image'];
            unset($all['image']);
        }
        unset($all['_method']);

        $product->update($all);
        if(isset($image)) {
            $product->addFile($image);
        }
        return $product;
    }
}
