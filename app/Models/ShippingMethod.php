<?php

namespace App\Models;

use App\Models\Traits\HasPrice;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
  use HasPrice;
  protected $guarded = [];
  protected $table = 'shipping_methods';

  public function countries()
  {
    return $this->belongsToMany(Country::class);
  }
}
