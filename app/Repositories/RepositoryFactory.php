<?php

namespace App\Repositories;


use App\Contracts\IRepository;
use App\Contracts\IRepositoryFactory;

class RepositoryFactory implements IRepositoryFactory
{
    protected $repositories;

    public function getRepository(string $className): IRepository
    {
        if (!isset($this->repositories[$className])) {
            $this->repositories[$className] = new Repository($className);
        }
        return $this->repositories[$className];
    }
}
