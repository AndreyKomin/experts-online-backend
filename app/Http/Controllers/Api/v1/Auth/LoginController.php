<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use Dingo\Api\Http\Response;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;


class LoginController extends Controller
{
    use Helpers;

    protected $auth;

    public function __construct(JWTAuth $JWTAuth)
    {
        $this->auth = $JWTAuth;
    }

    public function authenticate(Request $request): JsonResponse
    {
        $credentials = $request->only('login', 'password');

        if ($token = $this->auth->attempt($credentials)) {
            return response()->json([
                'token' => $token,
                'expires_in' => $this->auth->factory()->getTTL() * 60
            ]);
        }

        $this->response->errorBadRequest();
    }

    public function logout(): Response
    {
        $this->auth->invalidate();
        return $this->response->noContent();
    }

    public function refreshToken(): JsonResponse
    {
        $token = $this->auth->refresh();

        return response()->json([
            'token' => $token,
            'expires_in' => $this->auth->factory()->getTTL() * 60
        ]);

    }
}
