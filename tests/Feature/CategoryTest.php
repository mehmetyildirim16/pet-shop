<?php

namespace Tests\Feature;

use App\Models\Products\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
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
    public function test_admin_can_create_category()
    {
        $this->authenticate();
        //testing validation
        $this->withToken($this->token)->post('api/v1/admin/category/create', [
            'name' => 'test',
        ])->assertStatus(400);
        $this->withToken($this->token)->post('api/v1/admin/category/create', [
            'title' => 'test',
        ])->assertStatus(200);
        self::assertEquals(1, Category::all()->count());
    }

    public function test_admin_can_update_category()
    {
        $this->authenticate();
        $category = Category::factory()->create();
        $this->withToken($this->token)->put('api/v1/admin/category/'.$category->uuid, [
            'title' => 'test',
        ])->assertStatus(200);
        self::assertEquals('test', Category::find($category->id)->title);
    }

    public function test_admin_can_delete_category()
    {
        $this->authenticate();
        $category = Category::factory()->create();
        $this->withToken($this->token)->delete('api/v1/admin/category/'.$category->uuid)->assertStatus(200);
        self::assertEquals(0, Category::all()->count());
    }

}
