<?php

namespace App\Models\Btcmaster;

class Users extends BtcmasterBaseModel {
    public $timestamps = true;
    protected $guarded = ['id','created_at', 'updated_at', 'deleted_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     	 */
    protected $table = "btcmaster.users";


    /**
     * The attributes excluded from the model s JSON form.
     *
     * @var array
     	 */
    protected $hidden = [];

}