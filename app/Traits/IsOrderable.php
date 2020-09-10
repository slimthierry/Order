<?php

namespace App\Traits;


use Illuminate\Database\Eloquent\Builder;

trait IsOrderable
{

  public function scopeOrder(Builder $builder, $direction = 'Asc')
  {
    $builder->orderBy('order', $direction);
  }

}
