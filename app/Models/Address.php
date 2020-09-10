<?php

namespace App\Models;

use App\Models\Traits\CanBeDefault;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
  use CanBeDefault;
  protected $guarded = [];
  protected $casts = ['default' => 'boolean'];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function country()
  {
    return $this->hasOne(Country::class, 'id', 'country_id');
  }
}
