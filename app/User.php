<?php

namespace App;

use App\Models\Address;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\ProductVariation;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
  use Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'email', 'password','gateway_customer_id'
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
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public static function boot()
  {
    parent::boot();
    static::creating(function ($user) {
      $user->password = bcrypt($user->password);
    });
  }

  public function getJWTIdentifier()
  {
    return $this->id;
  }

  public function getJWTCustomClaims()
  {
    return [];
  }

  public function cart()
  {
    return $this->belongsToMany(ProductVariation::class, 'cart_user')
      ->withPivot('quantity')
      ->withTimestamps();
  }

  public function addresses()
  {
    return $this->hasMany(Address::class);
  }
  public function paymentMethods()
  {
    return $this->hasMany(PaymentMethod::class);
  }

  public function orders()
  {
    return $this->hasMany(Order::class);
  }
}
