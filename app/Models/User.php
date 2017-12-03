<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Tymon\JWTAuth\Contracts\JWTSubject;
use \Illuminate\Contracts\Auth\Authenticatable as AuthContract;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
 * @property Collection $availableMessengers
 * @property Collection $tags
 */
class User extends Eloquent implements JWTSubject, AuthContract
{
	use Authenticatable;

    const FIRST_NAME = 'first_name';
	const LAST_NAME = 'last_name';
    const RATING = 'rating';
	const LOGIN = 'login';
	const REMEMBER_TOKEN = 'remember_token';

	protected $table = 'users';

    protected $casts = [
		self::RATING => 'float'
	];

    protected $with = [
        'availableMessengers'
    ];

	protected $hidden = [
		'remember_token'
	];

	protected $fillable = [
		'first_name',
		'last_name',
		'login',
	];

	public function availableMessengers(): HasMany
	{
		return $this->hasMany(UserMessenger::class);
	}

	public function tags(): BelongsToMany
	{
		return $this->belongsToMany(Tag::class, 'user_tags');
	}

    public function getJWTIdentifier(): string
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function getMessengerUnique(int $messenger_id): ?string
    {
        /** @var UserMessenger $userMessenger */
        $userMessenger = UserMessenger::query()
            ->where('user_id', '=', $this->id)
            ->where('messenger_id','=',$messenger_id)
            ->first();
        return $userMessenger->messenger_unique_id;
    }

    public static function rules(): array
    {
        return [
            User::LOGIN => 'required|string|unique:users',
            User::FIRST_NAME => 'string|max:255',
            User::LAST_NAME => 'string|max:255',
        ];
    }
}
