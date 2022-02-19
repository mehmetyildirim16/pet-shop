<?php

namespace App\Actions;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Products\Product;
use App\Models\User;

class OrderAction
{

    public function create(array $data, User $user):Order
    {
        $products = $data['products'];
        $total_price = 0;
        foreach ($products as $product) {
            $the_product = Product::whereUuid($product['product'])->firstOrFail();
            $total_price += $the_product->price * $product['quantity'];
        }
        return Order::create([
            'user_id' => $user->id,
            'order_status_id' => OrderStatus::getDefaultStatus()->id,
            'products' => $data['products'],
            'address' => $data['address'],
            'delivery_fee' => $total_price >= 500 ? 0 : 15,
                      ]);
    }

    public function update(Order $order, array $data): Order
    {
        $products = $data['products'];
        $total_price = 0;
        foreach ($products as $product) {
            $the_product = Product::whereUuid($product['product'])->firstOrFail();
            $total_price += $the_product->price * $product['quantity'];
        }
        $order->update([
            'products' => $data['products'],
            'address' => $data['address'],
            'delivery_fee' => $total_price >= 500 ? 0 : 15,
        ]);
        return $order;
    }
}
