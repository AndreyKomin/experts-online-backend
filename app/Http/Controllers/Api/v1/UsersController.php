<?php

namespace App\Http\Controllers\Api\v1;

use App\Contracts\IRepository;
use App\Contracts\ITransformer;
use App\Events\MessengerAuthEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use App\Transformers\BaseTransformer;
use Dingo\Api\Http\Request;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Response;

class UsersController extends Controller
{
    use Helpers;

    protected $userService;
    protected $repository;

    public function __construct(UserService $service, IRepository $repository)
    {
        $this->userService = $service;
        $this->repository = $repository;
    }

    public function index(Request $request, ITransformer $transformer): Response
    {
        event(new MessengerAuthEvent(User::query()->first(), true));
        return $this->response->collection($this->repository->get(), $transformer);
    }

    public function update(Request $request): Response
    {
        $this->userService->save($this->user(), $request->all());
        return $this->response->noContent();
    }

    public function show(): Response
    {
        return $this->response->item($this->user(), new BaseTransformer());
    }

}
