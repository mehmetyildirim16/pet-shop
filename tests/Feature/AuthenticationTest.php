<?php

namespace Tests\Feature;

use App\Mails\ResetPasswordEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_authenticate(): void
    {
        $user = User::factory('App\User')->create([
                                                      'email' => 'john@doe.com',
                                                      'password' => bcrypt('secret'),
                                                  ]);

        $this->post('/api/v1/login', [
            'email' => $user->email,
            'password' => 'secret',
        ])->assertStatus(200);
    }

    public function test_user_cannot_authenticate_with_invalid_credentials(): void
    {
        $user = User::factory('App\User')->create([
                                                      'email' => 'john@doe.com',
                                                      'password' => bcrypt('secret'),
                                                  ]);
        $this->post('/api/v1/login', [
            'email' => $user->email,
            'password' => 'invalid',
        ])->assertStatus(401);
    }

    public function test_admin_can_login(): void
    {
        $user = User::factory('App\User')->create([
                                                      'email' => 'admin@buckhill.com',
                                                      'password' => bcrypt('secret'),
                                                      'is_admin' => true,
                                                  ]);
        $this->post('/api/v1/admin/login', [
            'email' => $user->email,
            'password' => 'secret',
        ])->assertStatus(200);
    }

    public function test_user_can_not_login_as_admin(): void
    {
        $user = User::factory('App\User')->create([
                                                      'email' => 'john@doe.com',
                                                      'password' => bcrypt('secret'),
                                                  ]);
        $this->post('/api/v1/admin/login', [
            'email' => $user->email,
            'password' => 'secret',
        ])->assertStatus(403);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory('App\User')->create([
                                                      'email' => 'john@doe.com',
                                                      'password' => bcrypt('secret'),
                                                  ]);
        $this->post('/api/v1/login', [
            'email' => $user->email,
            'password' => 'secret',
        ])->assertStatus(200);
        self::assertNotEquals(null, $user->getValidToken());
        $token = $user->getValidToken()->unique_id;
        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->post('/api/v1/logout')
            ->assertStatus(200);
        self::assertEquals(null, $user->getValidToken());
    }

    public function test_user_can_get_password_reset_link(): void
    {
        $user = User::factory('App\User')->create([
                                                      'email' => 'john@doe.com',
                                                      'password' => bcrypt('secret'),
                                                  ]);
        Mail::fake();
        $this->post('/api/v1/forgot-password', [
            'email' => $user->email,
        ])->assertStatus(200);
        Mail::assertSent(ResetPasswordEmail::class, function ($mail) use ($user) {
            return $mail->user->id === $user->id;
        });

        Mail::assertSent(ResetPasswordEmail::class);
    }

    public function test_user_can_reset_password(): void
    {
        $user = User::factory('App\User')
            ->create([
                         'email' => 'john@doe.com',
                     ]);
        $token = $user->remember_token;
        $this->post('/api/v1/reset-password?token=' . $token, [
            'email' => 'john@doe.com',
            'password' => 'supersecret',
            'password_confirmation' => 'supersecret',
        ])->assertStatus(200);
        $this->assertTrue(password_verify('supersecret', $user->fresh()->password));
    }
}
