<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * Messenger.
 * 
 * @property int $id
 * @property string $name
 * @property string $code
 * 
 * @property Collection $users
 */
class Messenger extends Eloquent
{
	const NAME = 'name';
	const CODE = 'code';
	const CAN_USE_FOR_MESSAGE = 'canUseForMessage';
    const CAN_USE_FOR_AUTH = 'canUseForAuth';

    public $timestamps = false;

	protected $fillable = [
		self::NAME,
		self::CODE,
        self::CAN_USE_FOR_MESSAGE,
        self::CAN_USE_FOR_AUTH,
	];

	public function users(): BelongsToMany
	{
		return $this->belongsToMany(User::class, 'user_messengers')
					->withTimestamps();
	}
}
