<?php
namespace App\Services\Contracts;

use App\Services\Contracts\Data\IDataProvider;

/**
 * Interface IStatusContractDataProvider
 * @package App\Contracts
 */
interface IStatusContractDataProvider extends IDataProvider
{
    /** Statuses */

    const
                ACCEPT = 'accept',
                DECLINED = 'declined',
                CANCEL = 'cancel',
                PROCESS = 'process';
}
