<?php

namespace App\Services\Contracts;

use App\Services\Contracts\Data\IDataProvider;


/**
 * Interface ITransitsProductsDataProvider
 * @package App\Services\Contracts
 */
interface ITransitsProductsDataProvider extends IDataProvider
{
    public function updateStatus();


}
