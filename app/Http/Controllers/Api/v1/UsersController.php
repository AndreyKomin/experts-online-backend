<?php

namespace App\Http\Controllers\Api\v1;

use App\Contracts\IRepositoryFactory;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserServiceManager;
use App\Transformers\BaseTransformer;
use Dingo\Api\Http\Request;
use Dingo\Api\Routing\Helpers;
use Dingo\Api\Http\Response;

class UsersController extends Controller
{
    use Helpers;

    protected $repositoryFactory;

    protected $serviceManager;

    public function __construct(
        UserServiceManager $serviceManager,
        IRepositoryFactory $repositoryFactory
    )
    {
        $this->repositoryFactory = $repositoryFactory->getRepository(User::class);
        $this->serviceManager = $serviceManager;
    }

    public function index(): Response
    {
        return $this->response->collection($this->repositoryFactory->get(), new BaseTransformer());
    }

    public function update(int $id, Request $request): Response
    {
        $this->serviceManager->update(User::query()->find($id), $request->toArray());
        return $this->response->noContent();
    }

    public function search(Request $request): Response
    {
        return $this->response->collection(
            $this->repositoryFactory->getQuery()
            ->where('portfolio', 'LIKE', '%' . $request->get('q') .'%')
            ->get(),
            new BaseTransformer()
        );

    }
}
