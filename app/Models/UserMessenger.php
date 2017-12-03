<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class UserMessenger
 * 
 * @property int $user_id
 * @property int $messenger_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $messenger_unique_id
 * 
 * @property \App\Models\Messenger $messenger
 * @property \App\Models\User $user
 */
class UserMessenger extends Eloquent
{
	const USER_ID = 'user_id';
	const MESSENGER_ID = 'messenger_id';
	const UNIQUE = 'messenger_unique_id';

    public $incrementing = false;

	protected $casts = [
		'user_id' => 'int',
		'messenger_id' => 'int',
	];

	protected $fillable = [
		'user_id',
		'messenger_id',
        'messenger_unique_id'
	];

	public function messenger(): BelongsTo
	{
		return $this->belongsTo(\App\Models\Messenger::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}

	public static function rules(): array
    {
        return [
            'messenger_unique_id' => 'required|string|unique:user_messengers,messenger_unique_id',
            'user_id' => 'required|int|exists:users,id',
            'messenger_id' => 'required|int|exists:messengers,id',
        ];
    }

}
