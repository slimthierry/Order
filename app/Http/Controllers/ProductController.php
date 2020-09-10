<?php

namespace App\Http\Controllers;

use App\Filtering\Filters\CategoryFilter;
use App\Http\Resources\ProductIndexResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
  public function index()
  {
    $products = Product::with(['variations.stock'])->withFilters($this->filters())->paginate(10);
    return ProductIndexResource::collection($products);
  }

  public function show(Product $product)
  {
    $product->load(['variations.product','variations.stock','variations.type']);
    return new ProductResource($product);
  }

  protected function filters()
  {
    return [
      'category' => new CategoryFilter()
    ];
  }
}
