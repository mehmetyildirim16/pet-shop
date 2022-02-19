<?php

use App\Models\UserToken;
use Illuminate\Http\Request;

/**
 * @throws \Illuminate\Auth\AuthenticationException
 */
function authUser(Request $request): \App\Models\User
{
    $token = $request->bearerToken();
    if(!$token) {
        throw new \Illuminate\Auth\AuthenticationException();
    }
    $user_token = UserToken::where('unique_id', $token)->first();
    if(!$user_token || $user_token->expires_at < now()) {
        throw new \Illuminate\Auth\AuthenticationException();
    }
    assert($user_token->user instanceof \App\Models\User);
    return $user_token->user;
}
