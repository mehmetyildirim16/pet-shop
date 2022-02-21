<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Http\Request;
use Tests\CreatesApplication;

class UserModelTest extends TestCase
{
    use RefreshDatabase;
    use CreatesApplication;

    public Model $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()
            ->create([
                'is_admin' => true,
                     ]);
        assert($this->user instanceof User);
    }

    /**
     * @test
     */
    public function get_user_token()
    {
        $token = $this->user->getValidToken();
        $this->assertNotNull($token);
        self::assertGreaterThan(now(), $token->expires_at);
    }

    /**
     * @test
     */
    public function get_user_by_token(){
        $token = $this->user->getValidToken();
        $user = User::getUserByToken($token->unique_id);
        $this->assertNotNull($user);
        $this->assertEquals($this->user->id, $user->id);
    }

    /**
     * @test
     */
    public function user_has_full_name_attribute(){
        self::assertNotNull($this->user->full_name);
        $this->assertEquals($this->user->full_name, $this->user->first_name . ' ' . $this->user->last_name);
    }

    /**
     * @test
     */
    public function get_auth_user(){
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer ' . $this->user->getValidToken()->unique_id);
        $authUser = authUser($request);
        self::assertEquals($this->user->id, $authUser->id);
    }

}
