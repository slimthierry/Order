<?php

namespace App\Services;

use App\Services\Contracts\ITransitsDataProvider;
use App\Traits\DataProvider;

class TransitsDataProvider implements ITransitsDataProvider
{
    use DataProvider;

    public function __construct()
    {

    }
}
