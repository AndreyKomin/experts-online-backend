<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface IRepository
{
    public function getQuery(): Builder;
    public function save(Model $model): Model;
    public function delete(Model $model): void;
    public function find(int $id): ?Model;
    public function getWhere(array $where): Collection;
    public function get(): Collection;
}