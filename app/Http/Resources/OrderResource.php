<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request $request
   * @return array
   */
  public function toArray($request)
  {
//    dd($this->total()->formatted());
    return [
      'id' => $this->id,
      'status' => $this->status,
      'created_at' => $this->created_at->toDateTimeString(),
      'subtotal' => $this->subtotal->formatted(),
      'total' => $this->total()->formatted(),
      'products' => ProductVariationResource::collection(
        $this->whenLoaded('products')
      ),
      'address' => AddressResource::collection(
        $this->whenLoaded('address')
      ),
//      'shippingMethod' => ShippingMethodResource::collection(
//        $this->whenLoaded('shippingMethod')
//      ),
    ];
  }
}
