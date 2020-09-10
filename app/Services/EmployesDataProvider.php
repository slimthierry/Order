<?php

namespace App\Services;


use App\Services\Contracts\IEmployesDataProvider;
use App\Traits\DataProvider;

class EmployesDataProvider implements IEmployesDataProvider
{

    use DataProvider;
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}


