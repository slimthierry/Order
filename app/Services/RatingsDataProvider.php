<?php

namespace App\Services;

use App\Services\Contracts\IRatingsDataProvider;
use App\Models\Rating;
use App\Traits\DataProvider;
use App\Traits\Ratingable;


class RatingsDataProvider implements IRatingsDataProvider
{
    use DataProvider, Ratingable;

    public function __construct( )
    {
        
    }

}
