<?php

namespace App\Repositories;

use App\Contracts\IRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Repository implements IRepository
{
    protected $className;

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function save(Model $model): Model
    {
        $model->save();
        return $model;
    }

    public function delete(Model $model): void
    {
        $model->delete();
    }

    public function find(int $id): ?Model
    {
        return $this->getQuery()->find($id);
    }

    public function getWhere(array $where): Collection
    {
        return $this->getQuery()->where($where)->get();
    }
    public function get(): Collection
    {
        return $this->getQuery()->get();
    }

    public function getQuery(): Builder
    {
        return call_user_func([$this->className, 'query']);
    }
}