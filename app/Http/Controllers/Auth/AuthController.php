<?php

namespace App\Http\Controllers\Auth;

use App\Actions\LoginAction;
use App\Config\JwtIssuer;
use App\Data\Responses\Auth\UserResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

class AuthController extends Controller
{

    private LoginAction $loginAction;

    public function __construct(LoginAction $loginAction)
    {
        $this->loginAction = $loginAction;
    }

    /**
     * @param Request $request
     * @OA\Post(
     *     path="/api/login",
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
     * @throws \Exception
     */
    public function loginAsUser(Request $request): JsonResponse
    {
        return $this->loginAction->logUserIn($request->all());
    }

    /**
     * @param Request $request
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
     *         )
     *     )
     * )
     * @throws \Exception
     */
    public function loginAsAdmin(Request $request): JsonResponse
    {
        return $this->loginAction->logAdminIn($request->all());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @OA\Get(
     *     path="/api/me",
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
        if(!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json(new UserResponse($user));
    }

    /**
     * @OA\Get(
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
     * @OA\Get(
     *     path="/api/v1/admin/logout",
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
    public function logoutAsAdmin(Request $request): JsonResponse
    {
        return $this->loginAction->logout($request->bearerToken());
    }
}
