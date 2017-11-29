<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Contracts\IRepositoryFactory;
use App\Contracts\IServiceManager;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Messenger;
use App\Models\UserMessenger;
use App\Transformers\BaseTransformer;
use Dingo\Api\Http\Response;
use Dingo\Api\Routing\Helpers;
use Illuminate\Contracts\Cache\Repository;
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

    public function __construct(
        JWTAuth $JWTAuth,
        IServiceManager $serviceManager,
        IRepositoryFactory $repositoryFactory
    ) {
        $this->auth = $JWTAuth;
        $this->repositoryFactory = $repositoryFactory;
        $this->serviceManager = $serviceManager;
    }

    public function authenticate(Request $request): Response
    {
        $request->validate([
            'code' => 'required|string|exists:messengers',
            'messenger_id' => 'required|string|exists:user_messengers,messenger_unique_id',
            'token' => 'string'
        ]);

        $messenger = $this->repositoryFactory->getRepository(Messenger::class)->getWhere([
            Messenger::CODE => $request->get('code')
        ])->first();

        $userMessenger = $this->repositoryFactory->getRepository(UserMessenger::class)->getWhere([
            UserMessenger::UNIQUE => $request->get('messenger_id'),
            UserMessenger::MESSENGER_ID => $messenger->id
        ])->first();

        /** @var User $user */

        $user = $this->repositoryFactory->getRepository(User::class)->find($userMessenger->user_id);

        return $this->proceedToken($user);
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

    public function register(Request $request): Response
    {
        $request->validate([
            'code' => 'required|string|exists:messengers',
            'messenger_id' => 'required|string|unique:user_messengers,messenger_unique_id',
            'token' => 'string',
            'login' => 'string|unique:users',
        ]);
        /** @var User $user */
        $data = $request->toArray();
        if (!$data['login']) {
            $latest = $this->repositoryFactory->getRepository(User::class)->getQuery()->latest();
            $data['login'] = 'id' . (string)($latest->id ?? 1);
        }

        $user = $this->serviceManager->create($request->toArray());

        $messenger = $this->repositoryFactory->getRepository(Messenger::class)->getWhere([
            Messenger::CODE => $request->get('code')
        ])->first();

        UserMessenger::query()->create([
            'messenger_id' => $messenger->id,
            'user_id' => $user->id,
            'messenger_unique_id' => $request->get('messenger_id'),
        ]);

        return $this->proceedToken($user);
    }

    public function update(Request $request): Response
    {
        $this->serviceManager->update($this->user, $request->toArray());
        return $this->response->noContent();
    }

    public function me(): Response
    {
        return $this->response->item($this->user, new BaseTransformer());
    }

    protected function proceedToken(JWTSubject $JWTSubject): Response
    {
        if (($token = $this->auth->fromSubject($JWTSubject))) {

            return $this->response->array([
                'token' => $token,
                'expires_in' => $this->auth->factory()->getTTL() * 60
            ]);
        }

        $this->response->errorForbidden();
    }

}
