<?php

namespace App\Contracts;


use Illuminate\Database\Eloquent\Model;

interface ITransformer
{
    public function transform(Model $model): array;
}