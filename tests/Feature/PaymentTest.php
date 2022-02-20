<?php

namespace Tests\Feature;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Orders\Payment;
use App\Models\Products\Brand;
use App\Models\Products\Category;
use App\Models\Products\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class PaymentTest extends TestCase
{

    use RefreshDatabase;
    use WithFaker;

    private string $token;

    private function authenticate(): User
    {
        $user = User::factory()
            ->create([
                         'is_admin' => true,
                     ]);
        $this->post('api/v1/admin/login', [
            'email' => $user->email,
            'password' => 'userpassword',
        ])->assertStatus(200);

        $this->token = $user->getValidToken()->unique_id;
        assert($user instanceof User);
        Auth::login($user);
        return $user;
    }

    private function seedDB(): void
    {
        $statuses = [
            'open',
            'pending payment',
            'paid',
            'shipped',
            'cancelled',
        ];
        foreach ($statuses as $status) {
            OrderStatus::create([
                                    'title' => $status,
                                ]);
        }
        Category::factory(5)->create();
        Brand::factory(5)->create();
        Product::factory(5)->create();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_view_payments()
    {
        $this->seedDB();
        User::factory(5)->create();
        $users = User::all();
        foreach ($users as $user) {
            Auth::login($user);
            Order::factory(5)
                ->create([
                             'user_id' => $user->id,
                         ]);
        }
        $user = $this->authenticate();
        Order::factory(5)
            ->create([
                         'user_id' => $user->id,
                     ]);
        $response = $this->withToken($this->token)->get('api/v1/payments')->assertStatus(200);
        $user_payments_count = Payment::all()->filter(fn($payment) => $payment->order->user_id === $user->id)->count();
        assertEquals($user_payments_count, $response->json()['total']);
    }

    public function test_user_can_create_and_view_payment_details()
    {
        $this->seedDB();
        $user = $this->authenticate();
        $products_array = [];
        $products = Product::all();
        foreach ($products as $product) {
            $quantity = random_int(1, 3);
            $products_array[] = [
                'product' => $product->uuid,
                'quantity' => $quantity,
            ];
        }
        $order_response = $this->withToken($this->token)->post('api/v1/order/create', [
            'products' => $products_array,
            'address' => [
                'shipping' => $this->faker->address,
                'billing' => $this->faker->address,
            ],
        ])->assertStatus(201);
        $order = Order::find($order_response->json()['id']);
        $order->order_status_id = OrderStatus::where('title', OrderStatus::STATUS_PENDING)->first()->id;
        $order->save();
        $response = $this->withToken($this->token)->post(
            'api/v1/payment/create',
            [
                'type' => 'credit_card',
                'order_uuid' => $order->refresh()->uuid,
                'details' => [
                    'number' => '123456789',
                    'holder_name' => 'John Doe',
                    'expire_date' => '12/22',
                    'cvv' => 123,
                ],
            ]
        )->assertStatus(200);
        $payment = Payment::whereUuid($response->json()['uuid'])->firstOrFail();
        $response = $this->withToken($this->token)->get('api/v1/payment/' . $payment->uuid)->assertStatus(200);
        assertEquals($payment->id, $response->json()['id']);
    }

    public function test_user_can_update_payment_details()
    {
        $this->seedDB();
        $user = $this->authenticate();
        $products_array = [];
        $products = Product::all();
        foreach ($products as $product) {
            $quantity = random_int(1, 3);
            $products_array[] = [
                'product' => $product->uuid,
                'quantity' => $quantity,
            ];
        }
        $order_response = $this->withToken($this->token)->post('api/v1/order/create', [
            'products' => $products_array,
            'address' => [
                'shipping' => $this->faker->address,
                'billing' => $this->faker->address,
            ],
        ])->assertStatus(201);
        $order = Order::find($order_response->json()['id']);
        $order->order_status_id = OrderStatus::where('title', OrderStatus::STATUS_PENDING)->first()->id;
        $order->save();
        $response = $this->withToken($this->token)->post(
            'api/v1/payment/create',
            [
                'type' => 'credit_card',
                'order_uuid' => $order->refresh()->uuid,
                'details' => [
                    'number' => '123456789',
                    'holder_name' => 'John Doe',
                    'expire_date' => '12/22',
                    'cvv' => 123,
                ],
            ]
        )->assertStatus(200);
        $payment = Payment::whereUuid($response->json()['uuid'])->firstOrFail();
        $response = $this->withToken($this->token)->put(
            'api/v1/payment/' . $payment->uuid,
            [
                'type' => 'credit_card',
                'order_uuid' => $order->refresh()->uuid,
                'details' => [
                    'number' => '123456789',
                    'holder_name' => 'John Doe',
                    'expire_date' => '12/22',
                    'cvv' => 123,
                ],
            ]
        )->assertStatus(200);
        $payment = Payment::whereUuid($response->json()['uuid'])->firstOrFail();
        $response = $this->withToken($this->token)->get('api/v1/payment/' . $payment->uuid)->assertStatus(200);
        assertEquals($payment->id, $response->json()['id']);
    }

    public function test_user_can_delete_payment()
    {
        $this->seedDB();
        $user = $this->authenticate();
        $products_array = [];
        $products = Product::all();
        foreach ($products as $product) {
            $quantity = random_int(1, 3);
            $products_array[] = [
                'product' => $product->uuid,
                'quantity' => $quantity,
            ];
        }
        $order_response = $this->withToken($this->token)->post('api/v1/order/create', [
            'products' => $products_array,
            'address' => [
                'shipping' => $this->faker->address,
                'billing' => $this->faker->address,
            ],
        ])->assertStatus(201);
        $order = Order::find($order_response->json()['id']);
        $order->order_status_id = OrderStatus::where('title', OrderStatus::STATUS_PENDING)->first()->id;
        $order->save();
        $response = $this->withToken($this->token)->post(
            'api/v1/payment/create',
            [
                'type' => 'credit_card',
                'order_uuid' => $order->refresh()->uuid,
                'details' => [
                    'number' => '123456789',
                    'holder_name' => 'John Doe',
                    'expire_date' => '12/22',
                    'cvv' => 123,
                ],
            ]
        )->assertStatus(200);
        $payment = Payment::whereUuid($response->json()['uuid'])->firstOrFail();
        $this->withToken($this->token)->delete('api/v1/payment/' . $payment->uuid)->assertStatus(200);
        $payment = Payment::whereUuid($payment->uuid)->first();
        self::assertNull($payment);
    }
}
