<?php

namespace App\Services\Contracts\Data;

interface IDataProviderHandlerParams
{

    /**
     * Returns the list of parameters to apply to the repository handler
     *
     * @return array
     */
    public function getParams();
}
