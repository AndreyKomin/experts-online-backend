<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 *
 * ag.
 * @property int $id
 * @property string $name
 * @property int $is_moderated
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property Collection $users
 */
class Tag extends Eloquent
{
	const NAME = 'name';
	const IS_MODERATED = 'is_moderated';

    protected $casts = [
		self::IS_MODERATED => 'int'
	];

	protected $fillable = [
		self::IS_MODERATED,
		self::NAME,
	];

	public function users(): BelongsToMany
	{
		return $this->belongsToMany(User::class, 'user_tags');
	}
}
