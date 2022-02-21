<?php

namespace Tests\Feature;

use App\Models\Blogs\Post;
use App\Models\Blogs\Promotion;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MainTest extends TestCase
{

    use RefreshDatabase;

    private string $token;

    private function authenticate(): Model
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
        return $user;
    }

    public function test_user_can_view_blogs(): void
    {
        $this->authenticate();
        Post::factory()->count(5)->create();
        $response = $this->get('api/v1/main/blog', [
            'Authorization' => 'Bearer ' . $this->token,
        ])->assertStatus(200);
        $response->assertJsonCount(5);
    }

    public function test_user_can_view_a_blog_post()
    {
        $this->authenticate();
        $post = Post::factory()->create();
        $response = $this->get('api/v1/main/blog/' . $post->uuid, [
            'Authorization' => 'Bearer ' . $this->token,
        ])->assertStatus(200);
        $response->assertJsonFragment([
                                          'id' => $post->id,
                                          'Title' => $post->title,
                                      ]);
    }

    public function test_user_can_view_current_promotions()
    {
        $this->authenticate();
        Promotion::factory(10)->create();
        $this->get('api/v1/main/promotions', [
            'Authorization' => 'Bearer ' . $this->token,
        ])->assertStatus(200);
    }
}
