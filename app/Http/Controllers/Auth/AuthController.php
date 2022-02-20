<?php

namespace App\Http\Controllers\Auth;

use App\Actions\LoginAction;
use App\Data\Responses\Auth\UserResponse;
use App\Http\Controllers\Controller;
use App\Mails\ResetPasswordEmail;
use App\Models\Orders\Payment;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use DB;

class AuthController extends Controller
{

    private LoginAction $loginAction;

    public function __construct(LoginAction $loginAction)
    {
        $this->loginAction = $loginAction;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @OA\Post(
     *     path="/api/v1/login",
     *     tags={"login"},
     *     operationId="loginUser",
     *     @OA\Responses(
     *         response=200,
     *         description="Successfuly logged in",
     *     ),
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="Users email",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="Users password",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function loginAsUser(Request $request): JsonResponse
    {
        return $this->loginAction->logUserIn($request->all());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @OA\Post(
     *     path="/api/v1/admin/login",
     *     tags={"login"},
     *     operationId="loginAdmin",
     *     @OA\Responses(
     *         response=200,
     *         description="Successfuly logged in",
     *     ),
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="Users email",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="Users password",
     *                     type="string"
     *                 )
     *             )
     *          )
     *      )
     * )
     */
    public function loginAsAdmin(Request $request): JsonResponse
    {
        return $this->loginAction->logAdminIn($request->all());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @OA\Get(
     *     path="/api/v1/me",
     *     tags={"me"},
     *     operationId="getMe",
     *     @OA\Responses(
     *         response=200,
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *          ),
     *        description="Logged in user attributes",
     *     ),
     *     security={
     *          {"bearer": {}}
     *     }
     *)
     */
    public function getUser(Request $request): JsonResponse
    {
        $user = User::getUserByToken($request->bearerToken());
        if ( ! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json(new UserResponse($user));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     tags={"logout"},
     *     operationId="logout",
     *     @OA\Responses(
     *         response=200,
     *         description="Successfuly logged out",
     *     ),
     *     security={
     *          {"bearer": {}}
     *     }
     * )
     */
    public function logoutAsUser(Request $request): JsonResponse
    {
        return $this->loginAction->logout($request->bearerToken());
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/logout",
     *     tags={"logout"},
     *     operationId="logout",
     *     @OA\Responses(
     *         response=200,
     *         description="Successfuly logged out",
     *     ),
     *     security={
     *          {"bearer": {}}
     *     },
     * )
     */
    public function logoutAsAdmin(Request $request): JsonResponse
    {
        return $this->loginAction->logout($request->bearerToken());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @OA\Post(
     *     path="/api/v1/forgot-password",
     *     tags={"forgot-password"},
     *     operationId="forgotPassword",
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *              mediaType="application/json",
     *               @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="email"
     *                      description="Users email",
     *                      type="string",
     *                  ),
     *                )
     *          )
     *      )
     *       @OA\Response(
     *            response=200,
     *            description="Successfuly sent email",
     *        ),
     *  )
     */

    public function sendResetPasswordEmail(Request $request): JsonResponse
    {
        $user = User::where('email', $request->email)->firstOrFail();
        Mail::send(new ResetPasswordEmail($user));
        return response()->json(['message' => 'Reset password email sent']);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/reset-password",
     *     tags={"reset-password"},
     *     operationId="resetPassword",
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *
     *                  @OA\Property(
     *                      property="email",
     *                      description="Users email",
     *                      type="string",
     *                  ),
     *                   @OA\Property(
     *                      property="password",
     *                      description="Users password",
     *                      type="string",
     *                  ),
     *                   @OA\Property(
     *                      property="password_confirmation",
     *                      description="Users password confirmation",
     *                      type="string",
     *                  ),
     *              ),
     *         ),
     *      ),
     *      @OA\Responses(
     *         response=200,
     *         description="Successfully reset password",
     *      ),
     *    )
     *
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|min:3|max:50',
            'password' => 'required|confirmed|min:8',
        ]);
        $user = User::where('email', $request->email)->firstOrFail();
        if ($user->remember_token !== $request->token) {
            return response()->json(['message' => 'Invalid token'], 400);
        }
        return DB::transaction(function () use ($user, $request) {
            $user->password = bcrypt($request->all()['password']);
            $user->save();
            PasswordReset::create([
                                      'email' => $user->email,
                                      'token' => $user->remember_token,
                                  ]);
            return response()->json(['message' => 'Password changed']);
        });
    }
}
