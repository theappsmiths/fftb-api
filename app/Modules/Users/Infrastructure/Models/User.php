<?php

namespace App\Modules\Users\Infrastructure\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

use Laravel\Passport\HasApiTokens;

use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, SoftDeletes, HasApiTokens;

    const ROLE_CUSTOMER = 'customer';
    const DEFAULT_STATUS = true;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'password', 'email', 'role', 
        'status', 'reason'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at', 'created_at', 'deleted_at',
        'password', 'status', 'reason'
    ];

    /**
     * Set default values of the attributes
     * 
     * @var array
     */
    protected $attributes = [
        'role' => self::ROLE_CUSTOMER,
        'status' => self::DEFAULT_STATUS
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'deleted_at' => 'timestamp',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'status' => 'boolean'
    ];

    /*******Methods for Getter Setter*********/
    public function setPasswordAttribute ($password) {
        $this->attributes['password'] = app('hash')->make($password);
    }


    /*********Method for Relations***************/

    public function profile () {
        return $this->hasOne (Profile::class, 'userId', 'id');
    }

    public function verification () {
        return $this->hasOne (\App\Models\User\Verification::class, 'userId', 'id');
    }

    public function device () {
        return $this->hasOne (\App\Models\User\Device::class, 'userId', 'id');
    }

    public function AauthAcessToken() {
        return $this->hasMany(Auth\OauthAccessToken::class, 'user_id', 'id');
    }

    public function log () {
        return $this->hasMany (\App\Models\Logs\User\User::class, 'userId', 'id');
    }
}
