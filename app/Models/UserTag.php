<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 05 Nov 2017 07:39:37 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class UserTag
 * 
 * @property int $user_id
 * @property int $tag_id
 * 
 * @property \App\Models\Tag $tag
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class UserTag extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'tag_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'tag_id'
	];

	public function tag()
	{
		return $this->belongsTo(\App\Models\Tag::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
