<?php

namespace App\Services;

use App\Models\Product;
use App\Services\Contracts\IProductsDataProvider;
use App\Traits\DataProvider;

class ProductsDataProvider implements IProductsDataProvider
{

    use DataProvider;

    public function __construct()
    {

    }

    public function productsByMenu($id)
    {
        return Product::where('menu_id', $id)->with(['price'])->get();
    }

    public function listsPersonal()
    {
        return $this->model->get(['id', 'name', 'price']);
    }
}
