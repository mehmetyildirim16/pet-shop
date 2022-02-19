<?php

namespace Tests\Feature;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Products\Brand;
use App\Models\Products\Category;
use App\Models\Products\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class OrderTest extends TestCase
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
    public function test_users_can_view_their_orders()
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
        $response = $this->withToken($this->token)->get('api/v1/orders')->assertStatus(200);
        self::assertCount(5, $response->json()['original']);
    }

    public function test_user_can_view_their_orders_by_uuid()
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
        $orders = Order::factory(5)
            ->create([
                         'user_id' => $user->id,
                     ]);
        $this->withToken($this->token)->get('api/v1/order/' . $orders->first()->uuid)->assertStatus(200);
    }

    public function test_user_can_create_order()
    {
        $this->seedDB();
        $this->authenticate();
        $products = Product::all();
        $products_array = [];
        foreach ($products as $product) {
            $quantity = random_int(1, 3);
            $products_array[] = [
                'product' => $product->uuid,
                'quantity' => $quantity,
            ];
        }
        $this->withToken($this->token)->post('api/v1/order/create', [
            'products' => $products_array,
            'address' => [
                'shipping' => $this->faker->address,
                'billing' => $this->faker->address,
            ],
        ])->assertStatus(201);
        self::assertEquals(1, Order::count());
    }

    public function test_user_can_update_order()
    {
        $this->seedDB();
        $user = $this->authenticate();
        $order = Order::factory()
            ->create([
                         'user_id' => $user->id,
                     ]);
        assert($order instanceof Order);
        $products = Product::all();
        $products_array = [];
        foreach ($products as $product) {
            $quantity = random_int(1, 3);
            $products_array[] = [
                'product' => $product->uuid,
                'quantity' => $quantity,
            ];
        }
        $this->withToken($this->token)->put('api/v1/order/' . $order->uuid, [
            'products' => $products_array,
            'address' => [
                'shipping' => 'test',
                'billing' => 'test',
            ],
        ])->assertStatus(200);
        self::assertEquals('test', $order->refresh()->address['shipping']);
        self::assertEquals('test', $order->refresh()->address['billing']);
    }

    public function test_user_can_delete_order()
    {
        $this->seedDB();
        $user = $this->authenticate();
        $order = Order::factory()
            ->create([
                         'user_id' => $user->id,
                     ]);
        assert($order instanceof Order);
        $this->withToken($this->token)->delete('api/v1/order/' . $order->uuid)->assertStatus(200);
        self::assertEquals(0, Order::count());
    }

}
