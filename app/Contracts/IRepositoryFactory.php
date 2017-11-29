<?php

namespace App\Contracts;

interface IRepositoryFactory
{
    public function getRepository(string $className): IRepository;
}