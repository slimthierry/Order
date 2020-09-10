<?php

namespace App\Services;

use App\Services\Contracts\ICustomersDataProvider;
use App\Traits\DataProvider;

class CustomersDataProvider implements ICustomersDataProvider
{

    use DataProvider;

    public function __construct()
    {

    }
}
