<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\TelegramAuth;
use App\Models\Repositories\MessengerRepository;
use App\Models\Repositories\UsersRepository;
use App\Models\User;
use App\Models\Messenger;
use Dingo\Api\Http\Response;
use Dingo\Api\Routing\Helpers;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

class LoginController extends Controller
{
    use Helpers, DispatchesJobs;

    protected $auth;

    protected $cacheRepository;

    protected $defaultExpireTime = 60;

    protected $usersRepository;

    protected $messengerRepository;

    public function __construct(
        JWTAuth $JWTAuth,
        Repository $cacheRepository,
        UsersRepository $usersRepository,
        MessengerRepository $messengerRepository
    ) {
        $this->auth = $JWTAuth;
        $this->cacheRepository = $cacheRepository;
        $this->usersRepository = $usersRepository;
        $this->messengerRepository = $messengerRepository;
    }

    public function authenticate(Request $request): Response
    {
        $login = $request->get('login');

        /** @var User $user */
        $user = $this->usersRepository->getQuery()->where(User::LOGIN, '=', $login)->first();
        /** @var Messenger $messenger */
        $messenger = $this->messengerRepository->find($request->get('messenger_id'));

        $key = $user->id . ':' . $messenger->code;

        if ($this->cacheRepository->has($key)) {
            return $this->response->array([
                'message' => ''
            ]);
        }

        if ($token = $this->auth->fromUser($user) && $messenger) {

            $key = $user->id . ':' . $messenger->code;

            $messengerUnique = $user->getMessengerUnique($messenger->id);

            if (!$messengerUnique) {
                $this->response->errorForbidden();
            }

            $this->cacheRepository->put($key, $token, $this->defaultExpireTime);

            $this->dispatchNow(new TelegramAuth($messengerUnique));

            return $this->response->noContent();
        }

        $this->response->errorForbidden();
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
