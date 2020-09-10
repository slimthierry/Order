<?php

namespace App\Listeners\Order;

use App\Cart\Cart;
use App\Events\Order\OrderCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmptyCart
{
  protected $cart;

  /**
   * EmptyCart constructor.
   * @param Cart $cart
   */
  public function __construct(Cart $cart)
  {
    $this->cart = $cart;
  }

  /**
   * Handle the event.
   *
   * @param  OrderCreated $event
   * @return void
   */
  public function handle(OrderCreated $event)
  {
    $this->cart->empty();
  }
}
