<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Contracts\IRepositoryFactory;
use App\Http\Controllers\Controller;
use App\Services\UserServiceManager;
use App\Transformers\BaseTransformer;
use Dingo\Api\Http\Response;
use Dingo\Api\Routing\Helpers;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    use Helpers, DispatchesJobs;

    protected $auth;

    protected $defaultExpireTime = 60;

    protected $repositoryFactory;

    protected $serviceManager;

    protected $serviceFactory;

    public function __construct(
        JWTAuth $JWTAuth,
        UserServiceManager $serviceManager,
        IRepositoryFactory $repositoryFactory
    ) {
        $this->auth = $JWTAuth;
        $this->repositoryFactory = $repositoryFactory;
        $this->serviceManager = $serviceManager;
    }

    public function authenticate(Request $request): Response
    {
        $request->validate([
            'provider' => 'required|string|exists:messengers,code',
            'messenger_unique' => 'string',
            'code' => 'string',
        ]);

        $user = $this->serviceManager->authenticate($request->toArray());
        return $this->response->array([
            'token' => $this->proceedToken($user),
            'user' => $user->toArray()
        ]);
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

    public function update(Request $request): Response
    {
        $request->validate([
            'login' => 'string|unique:users',
            'first_name' => 'string',
            'last_name' => 'string',
        ]);
        $this->serviceManager->update($this->user, $request->toArray());
        return $this->response->noContent();
    }

    public function me(): Response
    {
        return $this->response->item($this->user, new BaseTransformer());
    }

    protected function proceedToken(JWTSubject $JWTSubject): string
    {
        if (($token = $this->auth->fromSubject($JWTSubject))) {
            return $token;
        }

        throw new AuthenticationException();
    }

}
