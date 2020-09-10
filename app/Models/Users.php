<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


// class UserMember extends Model implements JWTSubject

class Users extends Authenticatable implements JWTSubject
{
    use Notifiable;

         /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        // 'firstname',
        // 'lastname',
        // 'email',
        // 'password',
        // 'phonenumber',
        // 'avatar',
        // 'employe_id',
        // 'username',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get JSON WEB TOKEN methods.
     *
     * @var array
     */
    public function getJWTIdentifier()
    {
      return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
      return [];
    }

    /**
     * Relationship.
     *
     * @var string
     */

      /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function employe()
    {
      return $this->belongsTo('App\Models\Employe');
    }
}
