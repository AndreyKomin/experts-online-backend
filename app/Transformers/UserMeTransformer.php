<?php

namespace App\Transformers;

use App\Models\User;
use App\Models\UserMessenger;
use Illuminate\Database\Eloquent\Model;

class UserMeTransformer extends BaseTransformer
{
    public function transform(Model $model): array
    {
        return $this->transformUser($model);
    }

    protected function transformUser(User $user): array
    {
        $data = parent::transform($user);
        $data['messengers'] = $user->messengers->mapWithKeys(function(UserMessenger $userMessenger) {
            return [$userMessenger->messenger->code => $userMessenger];
        })->toArray();
        return $data;
    }
}
