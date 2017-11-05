<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * User.
 * 
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $login
 * @property string $password
 * @property float $rating
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property Collection $messengers
 * @property Collection $tags
 */
class User extends Eloquent
{
	const RATING = 'rating';
	const LOGIN = 'login';
	const PASSWORD = 'password';
	const REMEMBER_TOKEN = 'remember_token';

    protected $casts = [
		self::RATING => 'float'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'first_name',
		'last_name',
		'login',
		'password',
		'rating',
		'remember_token'
	];

	public function messengers(): BelongsToMany
	{
		return $this->belongsToMany(Messenger::class, 'user_messengers')
					->withTimestamps();
	}

	public function tags(): BelongsToMany
	{
		return $this->belongsToMany(Tag::class, 'user_tags');
	}
}
