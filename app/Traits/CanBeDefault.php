<?php

namespace App\Traits;

use App\Filtering\Filter;
use Illuminate\Database\Eloquent\Builder;

trait CanBeDefault
{



  protected static function boot()
  {
    parent::boot();
    static::creating(function ($model) {
      if ($model->default) {
        $model->newQuery()->where('user_id', $model->user->id)->update([
          'default' => false
        ]);
      }
    });
  }

  public function setDefaultAttribute($value)
  {
    $this->attributes['default'] = ($value === true || $value ? true : false);
  }
}
