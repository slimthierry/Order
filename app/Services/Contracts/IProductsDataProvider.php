<?php

namespace App\Services\Contracts;

use App\Services\Contracts\Data\IDataProvider;


/**
 * Interface IProductsDataProvider
 * @package App\Services\Contracts
 */

interface IProductsDataProvider extends IDataProvider
{
    public function productsByMenu($id);

}
