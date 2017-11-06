<?php

namespace App\Http\Controllers\Api\v1;

use App\Contracts\IRepository;
use App\Contracts\ITransformer;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
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
        return $this->response->collection($this->repository->get(), $transformer);
    }

    public function update(User $user, Request $request): Response
    {
        $this->userService->save($user, $request->all());
        $this->response->noContent();
    }

    public function delete(User $user): Response
    {
        $this->userService->delete($user);
        return $this->response->noContent();
    }

}
