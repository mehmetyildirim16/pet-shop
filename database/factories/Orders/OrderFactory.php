<?php

namespace Database\Factories\Orders;

use App\Models\Orders\Order;
use App\Models\Orders\Payment;
use App\Models\Orders\OrderStatus;
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $order_status = OrderStatus::inRandomOrder()->first();
        $products = Product::inRandomOrder()->take(rand(1, 3))->get();
        $products_array = [];
        $total_price = 0;
        foreach ($products as $product) {
            $quantity = rand(1, 3);
            $products_array[] = [
                'product' => $product->uuid,
                'quantity' => $quantity,
            ];
            $total_price += $product->price * $quantity;
        }
        if($order_status->title === 'paid' || $order_status->title === 'shipped') {
            $order_payment = Payment::factory()->create();

        }

        return [
            'payment_id' => $order_payment->id ?? null,
            'order_status_id' => $order_status->id,
            'products' => $products_array,
            'address' => [
                'shipping' => $this->faker->address,
                'billing' => $this->faker->address,
            ],
            'delivery_fee' => $total_price >=500 ? 0 : 15,
            'shipped_at' => $order_status->title === 'shipped' ? now() : null,
        ];
    }
}
