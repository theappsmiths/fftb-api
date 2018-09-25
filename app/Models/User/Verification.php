<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Verification extends Model
{
    const ADD_STRING_AFTER_NUMBER_CHAR = 4;

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_user_verification';

    /**
     * Set primary key name
     * 
     * @var string
     */
    protected $primaryKey = 'userId';

    /**
     * Set primary key behaviour
     * 
     * @var boolean
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'token'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at', 'created_at', 'deleted_at'
    ];

    public function setTokenAttribute ($token) {
        $this->attributes['token'] = $this->setToken ($token);
    }

    public function getTokenAttribute ($token) {
        return wordwrap ($token, self::ADD_STRING_AFTER_NUMBER_CHAR, '-', true);
    }

    protected function setToken ($token) {
        return preg_replace ('/[^a-zA-Z0-9]/', '', trim ($token));
    }

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'deleted_at' => 'timestamp',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'mobile_verified' => 'boolean',
        'email_verified' => 'boolean',
    ];

    public function user () {
        return $this->belongsTo (\App\Modules\Users\Infrastructure\Models\User::class, 'userId', 'id');
    }

    public function scopeType ($query, string $type) {
        return $query->where ('type', trim ($type));
    }

    public function scopeToken ($query, string $token) {
        return $query->where ('token', trim ($this->setToken ($token)));
    }
}
