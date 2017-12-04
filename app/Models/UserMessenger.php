<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
    protected $primaryKey = ['user_id', 'messenger_id'];

	protected $casts = [
		'user_id' => 'int',
		'messenger_id' => 'int',
	];

	protected $fillable = [
		'user_id',
		'messenger_id',
        'messenger_unique_id',
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
            'messenger_unique_id' => 'required|string',
            'user_id' => 'required|int|exists:users,id',
            'messenger_id' => 'required|int|exists:messengers,id',
        ];
    }

    /**
     * Set the keys for a save update query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery(Builder $query)
    {
        $keys = $this->getKeyName();
        if(!is_array($keys)){
            return parent::setKeysForSaveQuery($query);
        }

        foreach($keys as $keyName){
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    /**
     * Get the primary key value for a save query.
     *
     * @param mixed $keyName
     * @return mixed
     */
    protected function getKeyForSaveQuery($keyName = null)
    {
        if(is_null($keyName)){
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }
}
