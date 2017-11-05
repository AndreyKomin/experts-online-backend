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
 * 
 * @property \App\Models\Messenger $messenger
 * @property \App\Models\User $user
 */
class UserMessenger extends Eloquent
{
	public $incrementing = false;

	protected $casts = [
		'user_id' => 'int',
		'messenger_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'messenger_id'
	];

	public function messenger(): BelongsTo
	{
		return $this->belongsTo(\App\Models\Messenger::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
