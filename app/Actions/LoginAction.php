<?php

namespace App\Actions;


use App\Config\JwtIssuer;
use App\Models\User;
use App\Models\UserToken;
use Illuminate\Http\JsonResponse;
use App\Data\Responses\Auth\LoginResponse;

class LoginAction
{
    public JwtIssuer $issuer;

    public function __construct(JwtIssuer $issuer)
    {
        $this->issuer = $issuer;
    }

    public function logAdminIn(array $credentials): JsonResponse
    {
        $user = User::whereEmail($credentials['email'])->first();
        if($user && !$user->isAdmin()) {
            return response()->json(['message' => 'You are not an admin'], 403);
        }
        return $this->execute($credentials);
    }

    public function logUserIn(array $credentials): JsonResponse
    {
        $user = User::whereEmail($credentials['email'])->first();
        if($user && $user->isAdmin()) {
            return response()->json(['message' => 'You are an admin'], 403);
        }
        return $this->execute($credentials);
    }

    public function execute(array $credentials):JsonResponse
    {
        // Validate the credentials
        // If invalid, return 401 Unauthorized
        // If valid, return 200 OK with the user object

        $user = User::whereEmail($credentials['email'])->first();
        if ( ! $user) {
            return response()
                ->json([
                           'message' => 'Invalid email',
                       ], 401);
        }
        $hasher = app('hash');
        if ( ! $hasher->check($credentials['password'], $user->password)) {
            return response()
                ->json([
                           'message' => 'Invalid password',
                       ], 401);
        }
        $token = $this->issuer->issueToken();
        $user->last_login_at = now();
        $user->save();
        $user->addToken($token);

        return (new LoginResponse( $token->claims()->toString()))->jsonSerialize();
    }

    public function logout(?string $bearerToken):JsonResponse
    {
        $token = UserToken::whereUniqueId($bearerToken)->first();
        if($token) {
            $token->delete();
        }
        return response()->json(['message' => 'Logged out']);
    }
}
