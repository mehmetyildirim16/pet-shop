<?php

namespace Tests\Feature;

use App\Models\Products\Brand;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandTest extends TestCase
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
    public function test_admin_can_create_brand()
    {
        $this->authenticate();
        //testing validation
        $this->withToken($this->token)->post('api/v1/admin/brand/create', [
            'name' => 'test',
        ])->assertStatus(400);
        $this->withToken($this->token)->post('api/v1/admin/brand/create', [
            'title' => 'test',
        ])->assertStatus(200);
        self::assertEquals(1, Brand::all()->count());
    }

    public function test_admin_can_update_brand()
    {
        $this->authenticate();
        $brand = Brand::factory()->create();
        $this->withToken($this->token)->put('api/v1/admin/brand/'.$brand->uuid, [
            'title' => 'test',
        ])->assertStatus(200);
        self::assertEquals('test', Brand::find($brand->id)->title);
    }

    public function test_admin_can_delete_brand()
    {
        $this->authenticate();
        $brand = Brand::factory()->create();
        $this->withToken($this->token)->delete('api/v1/admin/brand/'.$brand->uuid)->assertStatus(200);
        self::assertEquals(0, Brand::all()->count());
    }

    public function test_admin_can_get_brand()
    {
        $this->authenticate();
        $brand = Brand::factory()->create();
        $this->withToken($this->token)->get('api/v1/admin/brand/'.$brand->uuid)->assertStatus(200);
    }

    public function test_admin_can_get_brands()
    {
        $this->authenticate();
        Brand::factory(10)->create();
        $response = $this->withToken($this->token)->get('api/v1/admin/brands')->assertStatus(200);
        self::assertCount(10, $response->json());
    }

}
