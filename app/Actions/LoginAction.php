<?php

namespace App\Actions;


use App\Config\JwtIssuer;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class LoginAction
{
    public JwtIssuer $issuer;

    public function __construct(JwtIssuer $issuer)
    {
        $this->issuer = $issuer;
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


        $user->addToken($token);

        return response()
            ->json([
                       'token' => $token->claims()->toString(),
                   ]);
    }
}
