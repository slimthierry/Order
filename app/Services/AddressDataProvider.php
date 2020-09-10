<?php

namespace App\Services;

use App\Services\Contracts\IAddressDataProvider;
use App\Traits\DataProvider;

class AddressDataProvider implements IAddressDataProvider
{

    use DataProvider;
    
    public function __construct()
    {

    }


}
