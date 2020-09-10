<?php

namespace App\Cart\Payments;

use App\Models\PaymentMethod;


interface GatewayCustomer
{
  public function id();

  public function addCard($token);

  public function charge(PaymentMethod $card, $amount);
}