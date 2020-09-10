<?php

namespace App\Traits;

use App\Filtering\Filter;
use Illuminate\Database\Eloquent\Builder;

trait CanBeFiltered
{

  public function scopeWithFilters(Builder $builder, $filters = [])
  {
    $filter = new Filter(request());
    return $filter->apply($builder, $filters);
  }
}
