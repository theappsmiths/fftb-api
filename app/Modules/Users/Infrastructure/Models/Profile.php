<?php

namespace App\Modules\Users\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_user_profile';

    /**
     * Set primary key name
     * 
     * @var string
     */
    protected $primaryKey = 'userId';

    /**
     * Design additional fields
     * 
     * @var array
     */
    protected $appends = ['name'];

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
        'firstName', 'lastName', 'mobile', 'image',
        'countryId', 'address', 'postCode', 
        'mobile_verified', 'email_verified'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at', 'created_at', 'deleted_at',
        'password', 'countryId'
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
        'email_verified' => 'boolean',
        'mobile_verified' => 'boolean',
    ];

    public function user () {
        return $this->belongsTo (User::class, 'userId', 'id');
    }

    public function country () {
        return $this->belongsTo (\App\Models\Country::class, 'countryId', 'id');
    }

    public function getNameAttribute () {
        return $this->firstName . ' ' . $this->lastName;
    }
}
