<?php

namespace App\Http\Controllers;

use App\Cart\Payments\Gateway;
use App\Http\Requests\PaymentMethods\PaymentMethodStoreRequest;
use App\Http\Resources\PaymentMethodResource;
use Illuminate\Http\Request;


class PaymentMethodController extends Controller
{
  protected $gateway;

  public function __construct(Gateway $gateway)
  {
    $this->middleware(['auth:api']);
    $this->gateway = $gateway;
  }

  public function index(Request $request)
  {
    return PaymentMethodResource::collection($request->user()->paymentMethods);
  }

  public function store(PaymentMethodStoreRequest $request)
  {
    $card = $this->gateway->withUser($request->user())
      ->createCustomer($request->token)
      ->addCard($request->token);

    return new PaymentMethodResource($card);
  }
}
