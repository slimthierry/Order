<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request $request
   * @return array
   */
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'slug' => $this->slug,
      'description' => $this->description,
      'price' => $this->formattedPrice,
      'stock_count' => $this->stockCount(),
      'in_stock' => $this->inStock(),
      'variations' => ProductVariationResource::collection(
        $this->variations->groupBy('type.name')
      )
    ];
//    return array_merge(parent::toArray($request), [
//      'variations' => ProductVariationResource::collection(
//        $this->variations->groupBy('type.name')
//      )]);
  }
}
