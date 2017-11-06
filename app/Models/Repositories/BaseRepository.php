<?php

namespace App\Models\Repositories;

use App\Contracts\IRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class BaseRepository implements IRepository
{
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

}
