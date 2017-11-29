<?php
namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

interface IServiceManager
{
    public function create(array $data): Model;
    public function update(Model $model, array $data): Model;
    public function delete(Model $model): void;
}