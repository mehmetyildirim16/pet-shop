<?php

namespace App\Data\Responses\Auth;

use Illuminate\Http\JsonResponse;
use JsonSerializable;

class LoginResponse implements JsonSerializable
{

    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function jsonSerialize():JsonResponse
    {
        return response()->json([
            'token' => $this->token
        ]);
    }
}
