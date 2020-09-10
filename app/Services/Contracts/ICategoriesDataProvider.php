<?php

namespace App\Services\Contracts;

use App\Services\Contracts\Data\IDataProvider;

/**
 * Interface ICategoriesDataProvider
 * @package App\Services\Contracts
 */
interface ICategoriesDataProvider extends IDataProvider
{
    public function getCategory($category_id);



}
