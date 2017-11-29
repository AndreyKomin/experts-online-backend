<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\MessengerAuthEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\BotsDecisionRequest;
use App\Models\Repositories\MessengerRepository;
use App\Models\Repositories\UserMessengerRepository;
use App\Models\User;
use App\Services\UserServiceManager;
use App\Transformers\BaseTransformer;

use Dingo\Api\Http\Response;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;

class BotController extends Controller
{
    use Helpers;

    protected $messengerRepository;

    protected $userMessengerRepository;

    protected $userService;

    public function __construct(
        MessengerRepository $messengerRepository,
        UserMessengerRepository $userMessengerRepository,
        UserServiceManager $userService
    ) {
        $this->messengerRepository = $messengerRepository;
        $this->userMessengerRepository = $userMessengerRepository;
        $this->userService = $userService;
    }

    public function decision(Request $request): Response
    {

        $userMessenger = $this->userMessengerRepository->findOrFailByUniqueAndMessenger(
            $request->get('chatId'),
            $this->messengerRepository->findOrFailByCode($request->get('code'))
        );

        event(new MessengerAuthEvent($userMessenger->user, $request->get('decision')));
        return $this->response->noContent();
    }

    public function register(Request $request): Response
    {
        $messenger = $this->messengerRepository->findOrFailByCode($request->get('code'));
        $user = $this->userService->createUserWithMessenger([
            User::LOGIN => $request->get('login'),
        ], $messenger, $request->get('chatId'));

        return $this->response->item($user, new BaseTransformer);
    }
}
