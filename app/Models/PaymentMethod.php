<?php

namespace App\Models;

use App\Models\Traits\CanBeDefault;
use App\User;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
  use CanBeDefault;
  protected $casts = ['default' => 'boolean'];
  protected $guarded = [];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
