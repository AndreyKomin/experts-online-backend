<?php

namespace App\Http\Controllers\Api\v1;

use App\Contracts\IRepository;
use App\Contracts\IRepositoryFactory;
use App\Contracts\ITransformer;
use App\Events\MessengerAuthEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserServiceManager;
use App\Transformers\BaseTransformer;
use Dingo\Api\Http\Request;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Response;

class UsersController extends Controller
{
    use Helpers;

    protected $repository;

    public function __construct(IRepositoryFactory $repositoryFactory)
    {
        $this->repository = $repositoryFactory->getRepository(User::class);
    }

    public function index(): Response
    {
        return $this->response->collection($this->repository->get(), new BaseTransformer());
    }
}
