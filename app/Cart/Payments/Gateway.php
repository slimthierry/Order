<?php

namespace App\Cart\Payments;


use App\User;

interface Gateway
{
  public function withUser(User $user);

  public function createCustomer();
}