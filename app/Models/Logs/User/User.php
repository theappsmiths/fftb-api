<?php

namespace App\Models\Logs\User;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model {
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_users';

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
        'userId', 'updatedBy', 'changeset', 
        'previousState', 'newState'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at', 'created_at', 'deleted_at', 
        'userId', 'updatedBy'
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
        'changeset' => 'json',
        'previousState' => 'json',
        'newState' => 'json'
    ];

    public function user () {
        return $this->belongsTo (\App\Modules\Users\Infrastructure\Models\User::class, 'userId', 'id');
    }

    public function updatedBy () {
        return $this->belongsTo (\App\Modules\Users\Infrastructure\Models\User::class, 'userId', 'id');
    }
}