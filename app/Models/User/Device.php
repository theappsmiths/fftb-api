<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_user_device_detail';

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
        'deviceDetail'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at', 'created_at', 'deleted_at'
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
        'deviceDetail' => 'json'
    ];

    public function user () {
        return $this->belongsTo (\App\Modules\Users\Infrastructure\Models\User::class, 'userId', 'id');
    }
}
