<?php

namespace App\Transformers;

use App\Contracts\ITransformer;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;

class BaseTransformer extends TransformerAbstract implements ITransformer
{
    public function transform(Model $model): array
    {
        return $model->toArray();
    }
}