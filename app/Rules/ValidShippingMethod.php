<?php

namespace App\Rules;

use App\Models\Address;
use Illuminate\Contracts\Validation\Rule;

class ValidShippingMethod implements Rule
{
  protected $address;

  /**
   * ValidShippingMethod constructor.
   * @param $address
   */
  public function __construct($address)
  {
    $this->address = $address;
  }

  /**
   * Determine if the validation rule passes.
   *
   * @param  string $attribute
   * @param  mixed $value
   * @return bool
   */
  public function passes($attribute, $value)
  {
    if (!$this->address) {
      return false;
    }
    return $this->address->country->shippingMethods->contains('id',$value);
  }

  /**
   * Get the validation error message.
   *
   * @return string
   */
  public function message()
  {
    return 'Invalid shipping method.';
  }
}
