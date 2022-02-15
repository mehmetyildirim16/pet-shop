<?php

namespace App\Http\Controllers\Auth;

use App\Actions\LoginAction;
use App\Config\JwtIssuer;
use App\Http\Controllers\Controller;
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
     *     path="/login",
     *     tags={"login"},
     *     operationId="loginUser",
     *     @OA\Response(
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
    public function login(Request $request): JsonResponse
    {
        return $this->loginAction->execute($request->all());
    }
}
