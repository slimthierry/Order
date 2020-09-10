<?php
namespace App\Services\Contracts;

use App\Services\Contracts\Data\IDataProvider;

/**
 * Interface IGatewayContractDataProvider
 * @package App\Services\Contracts
 */
interface IGatewayContractDataProvider extends IDataProvider
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getDriver(): string;

    /**
     * @return array
     */
    public function getParams(): array;
}
