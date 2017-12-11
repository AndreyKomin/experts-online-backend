<?php

namespace App\Http\Controllers\Api\v1;

use App\Contracts\IRepositoryFactory;
use App\Http\Controllers\Controller;
use App\Models\Messenger;
use App\Transformers\BaseTransformer;
use Dingo\Api\Http\Response;
use Dingo\Api\Routing\Helpers;

class MessengersController extends Controller
{
    use Helpers;

    protected $repositoryFactory;

    public function __construct(IRepositoryFactory $repositoryFactory)
    {
        $this->repositoryFactory = $repositoryFactory;
    }

    public function index(): Response
    {
        return $this->response->array(
            $this->repositoryFactory->getRepository(Messenger::class)
                ->get()
                ->mapWithKeys(function(Messenger $messenger) {
                    return [$messenger->code => $messenger];
                })
                ->toArray()
        );
    }
}
