<?php

namespace Tests\Feature;

use App\Models\Orders\OrderStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderStatusTest extends TestCase
{

    use RefreshDatabase;
    private string $token;

    private function authenticate():Model
    {
        $user = User::factory()
            ->create([
                         'is_admin' => true,
                     ]);
        $this->post('api/v1/admin/login', [
            'email'    => $user->email,
            'password' => 'userpassword',
        ])->assertStatus(200);

        $this->token = $user->getValidToken()->unique_id;
        return $user;
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_can_create_order_status()
    {
        $this->authenticate();
        $this->withToken($this->token)->post('api/v1/admin/order-status/create', [
            'title' => 'test',
        ])->assertStatus(200);
        assert(OrderStatus::where('title', 'test')->exists());
    }

    public function test_admin_can_update_order_status()
    {
        $this->authenticate();
        $orderStatus = OrderStatus::factory()->create();
        $this->withToken($this->token)->put('api/v1/admin/order-status/' . $orderStatus->uuid, [
            'title' => 'test',
        ])->assertStatus(200);
        assert(OrderStatus::where('title', 'test')->exists());
    }

    public function test_admin_can_delete_order_status()
    {
        $this->authenticate();
        $orderStatus = OrderStatus::factory()->create();
        $this->withToken($this->token)->delete('api/v1/admin/order-status/' . $orderStatus->uuid)->assertStatus(200);
        assert(!OrderStatus::where('uuid', $orderStatus->uuid)->exists());
    }

    public function test_admin_can_get_order_status()
    {
        $this->authenticate();
        $orderStatus = OrderStatus::factory()->create();
        $this->withToken($this->token)->get('api/v1/admin/order-status/' . $orderStatus->uuid)->assertStatus(200);
    }

    public function test_admin_can_get_order_statuses()
    {
        $this->authenticate();
        OrderStatus::factory(10)->create();
        $response = $this->withToken($this->token)->get('api/v1/admin/order-statuses')->assertStatus(200);
        self::assertCount(10, $response->json());
    }
}
