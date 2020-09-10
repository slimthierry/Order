<?php
namespace App\Services\Contracts;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Omnipay\Common\Exception\InvalidResponseException;
use App\Http\Exceptions\OperationException;
use App\Http\Requests\CheckRequest;
use App\Http\Requests\CompleteRequest;
use App\Http\Requests\PurchaseRequest;
use App\Services\Contracts\Data\IDataProvider;

/**
 * Interface IDriverContractDataProvider
 * @package App\Services\Contracts
 */
interface IDriverContractDataProvider extends IDataProvider
{
    /**
     * @param IPaymentContractDataProvider       $contract
     * @param IPaymentDataProvider|null $payment
     *
     * @return PurchaseRequest
     */
    public function purchase(IPaymentContractDataProvider $contract, IPaymentDataProvider $payment = null): PurchaseRequest;

    /**
     * @param Request               $request
     * @param IPaymentDataProvider|null $payment
     *
     * @return CompleteRequest
     */
    public function complete(Request $request, IPaymentDataProvider $payment = null): CompleteRequest;

    /**
     * @param IPaymentContractDataProvider $contract
     * @param array           $reference
     *
     * @return CheckRequest
     * @throws OperationException
     */
    public function check(IPaymentContractDataProvider $contract, $reference = []): CheckRequest;

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function success(Request $request): Response;

    /**
     * @param Request $request
     * @param string  $message
     *
     * @return Response
     */
    public function failed(Request $request, string $message = null): Response;

}
