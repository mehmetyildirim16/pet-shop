<?php

namespace Tests\Feature;

use App\Models\Products\Brand;
use App\Models\Products\Category;
use App\Models\Products\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Webmozart\Assert\Mixin;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

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

    private function seedDB(){
        Category::factory(10)->create();
        Brand::factory(10)->create();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_can_create_product()
    {
        $this->authenticate();
        $this->seedDB();
        //testing validation
        $category = Category::inRandomOrder()->first();
        $brand = Brand::inRandomOrder()->first();
        $this->withToken($this->token)->post('api/v1/admin/product/create', [
            'category_uuid' => $category->uuid,
            'description' => $this->faker->paragraph(3),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'brand' => $brand->uuid,
            'image' => $this->faker->imageUrl(200, 200),
        ])->assertStatus(400);
        $this->withToken($this->token)->post('api/v1/admin/product/create', [
            'title' => $this->faker->sentence(2),
            'category_uuid' => $category->uuid,
            'description' => $this->faker->paragraph(3),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'brand' => $brand->uuid,
            'image' => $this->faker->imageUrl(200, 200),
        ])->assertStatus(200);
        self::assertEquals(1, Product::all()->count());
    }

    public function test_admin_can_update_product()
    {
        $this->authenticate();
        $this->seedDB();
        $category = Category::inRandomOrder()->first();
        $brand = Brand::inRandomOrder()->first();
        $product = Product::factory()->create();
        $this->withToken($this->token)->post('api/v1/admin/product/'.$product->uuid.'?_method=PUT', [
            'title' => 'test',
            'category_uuid' => $category->uuid,
            'description' => $this->faker->paragraph(3),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'brand' => $brand->uuid,
            'image' => $this->faker->imageUrl(200, 200),
        ])->assertStatus(200);
        self::assertEquals('test', Product::find($product->id)->title);
    }

    public function test_admin_can_delete_product()
    {
        $this->authenticate();
        $this->seedDB();
        $product = Product::factory()->create();
        $this->withToken($this->token)->delete('api/v1/admin/product/'.$product->uuid)->assertStatus(200);
        self::assertEquals(0, Product::all()->count());
    }

    public function test_admin_can_get_product()
    {
        $this->authenticate();
        $this->seedDB();
        $product = Product::factory()->create();
        $this->withToken($this->token)->get('api/v1/admin/product/'.$product->uuid)->assertStatus(200);
    }

    public function test_admin_can_get_products()
    {
        $this->authenticate();
        $this->seedDB();
        Product::factory(10)->create();
        $response = $this->withToken($this->token)->get('api/v1/admin/products')->assertStatus(200);
        self::assertCount(10, $response->json());
    }
}
