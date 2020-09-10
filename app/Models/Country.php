<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
  public $timestamps = false;
  protected $guarded = [];

  public function shippingMethods()
  {
    return $this->belongsToMany(ShippingMethod::class,'shipping_method_country');
  }
}
