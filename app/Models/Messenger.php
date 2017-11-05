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

    public $timestamps = false;

	protected $fillable = [
		self::NAME,
		self::CODE,
	];

	public function users(): BelongsToMany
	{
		return $this->belongsToMany(User::class, 'user_messengers')
					->withTimestamps();
	}
}
