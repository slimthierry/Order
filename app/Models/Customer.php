<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    //
    protected $fillable = ['name', 'phonenumber', 'address_id', 'user_member_id'];

    protected $dates = ['deleted_at'];

    use SoftDeletes;

      /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function address()
    {
        return $this->belongsToMany('App\Models\Address');
    }

      /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deliverance()
    {
        return $this->hasMany('App\Models\Deliverance');
    }

         /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userMember()
    {
        return $this->hasOne('App\Models\UserMember');
    }

}

