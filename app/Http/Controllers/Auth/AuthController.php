<?php

namespace App\Http\Controllers\Auth;

use App\Actions\LoginAction;
use App\Data\Responses\Auth\UserResponse;
use App\Http\Controllers\Controller;
use App\Mails\ResetPasswordEmail;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    private LoginAction $loginAction;

    public function __construct(LoginAction $loginAction)
    {
        $this->loginAction = $loginAction;
    }


    public function loginAsUser(Request $request): JsonResponse
    {
        return $this->loginAction->logUserIn($request->all());
    }


    public function loginAsAdmin(Request $request): JsonResponse
    {
        return $this->loginAction->logAdminIn($request->all());
    }


    public function getUser(Request $request): JsonResponse
    {
        $user = User::getUserByToken($request->bearerToken());
        if ( ! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json(new UserResponse($user));
    }


    public function logoutAsUser(Request $request): JsonResponse
    {
        return $this->loginAction->logout($request->bearerToken());
    }

    public function logoutAsAdmin(Request $request): JsonResponse
    {
        return $this->loginAction->logout($request->bearerToken());
    }


    public function sendResetPasswordEmail(Request $request): JsonResponse
    {
        $user = User::where('email', $request->email)->firstOrFail();
        $token = Str::random(60);
        PasswordReset::create([
                                  'email' => $user->email,
                                  'token' => $token,
                              ]);
        Mail::send(new ResetPasswordEmail($user, $token));
        return response()->json(['message' => 'Reset password email sent']);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|min:3|max:50',
            'password' => 'required|confirmed|min:8',
        ]);
        return DB::transaction(function () use ($request) {
            $token = PasswordReset::where('token', $request->token)->firstOrFail();
            $user = User::where('email', $token->email)->firstOrFail();
            $user->password = bcrypt($request->all()['password']);
            $user->save();
            $token->delete();
            return response()->json(['message' => 'Password changed']);
        });
    }
}
