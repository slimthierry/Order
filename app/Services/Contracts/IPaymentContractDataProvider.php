<?php
namespace App\Services\Contracts;

use App\Services\Contracts\Data\IDataProvider;

/**
 * Interface IPaymentContractDataProvider
 * @package App\Services\Contracts
 */

interface IPaymentContractDataProvider extends IDataProvider
{
    /**
     * PaymentContract constructor.
     *
     * @param                 $payment_id
     * @param IGatewayContractDataProvider $gateway
     * @param float           $amount
     * @param array           $attributes
     */
    public function __construct($payment_id, IGatewayContractDataProvider $gateway, float $amount, array $attributes = []);

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return IGatewayContractDataProvider
     */
    public function getGateway(): IGatewayContractDataProvider;

    /**
     * @return string
     */
    public function getGatewayName(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return string
     */
    public function getDriver(): string;

    /**
     * @return float
     */
    public function getAmount(): float;

    /**
     * @return array
     */
    public function getCustomer(): array;

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function getCustomerValue(string $key);

    /**
     * @param array $params
     *
     * @return string
     */
    public function getNotifyUrl(array $params = []): string;

    /**
     * @param array $params
     *
     * @return string
     */
    public function getResultUrl(array $params = []): string;

    /**
     * @param array $params
     *
     * @return string
     */
    public function getSuccessUrl(array $params = []): string;

    /**
     * @param array $params
     *
     * @return string
     */
    public function getReturnUrl(array $params = []): string;

    /**
     * @param array $params
     *
     * @return string
     */
    public function getFailedUrl(array $params = []): string;

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getAttributeByKey(string $key);
}
