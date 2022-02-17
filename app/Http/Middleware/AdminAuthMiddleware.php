<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = User::getUserByToken($request->bearerToken());
        if (!$user || !$user->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $token = $user->getValidToken();
        if ($token) {
            $token->expires_at = Carbon::now()->addMinutes(config('app.jwt_ttl'));
            $token->last_used_at = Carbon::now();
            $token->save();
        }
        return $next($request);
    }
}
