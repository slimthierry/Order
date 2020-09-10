<?php

namespace App\Services;


use App\Exceptions\InvalidResponseException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\DataProvider;
use App\Services\Contracts\DriverContract;
use App\Services\Contracts\PaymentContract;
use App\Services\Contracts\PaymentInterface;
use App\Events\PurchaseComplete;
use App\Events\PurchaseFailed;
use App\Events\PurchaseStart;
use App\Exceptions\PurchaseException;
use App\Http\Requests\CompleteRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Status;
use App\Services\Contracts\IGatewayContractDataProvider;
use App\Services\Contracts\IPaymentContractDataProvider;
use App\Services\Contracts\IPaymentDataProvider;
use App\Services\Contracts\IStatusContractDataProvider;

class Payment implements IPaymentDataProvider
{

    use DataProvider;

      /**
     * @var Application
     */
    private $app;

    /**
     * PaymentService constructor.
     *
     * @param               $app
     * @param
     */
    public function __construct($app)
    {
        $this->app     = $app;
        // $this->factory = $factory;
    }

    /**
     * @param IPaymentContractDataProvider       $contract
     * @param IPaymentDataProvider|null $payment
     *
     * @return PurchaseRequest
     * @throws PurchaseException
     */
    function purchase(IPaymentContractDataProvider $contract, IPaymentDataProvider $payment = null): PurchaseRequest
    {
        /** @var DriverContract $driver */

        if (!$driver = $this->factory->create($contract->getGateway())) {
            throw new PurchaseException("Driver {$contract->getDriver()} not found");
        }

        /** @var PurchaseRequest $request */
        $request = $driver->purchase($contract, $payment);

        // event(new PurchaseStart($contract, $request));

        return $request;
    }

    /**
     * @param Gateway               $gateway
     * @param Request               $request
     * @param IPaymentDataProvider|null $payment
     *
     * @return Response
     * @throws Exceptions\OperationException
     * @throws PurchaseException
     */
    function complete(IGatewayContractDataProvider $gateway, Request $request, IPaymentDataProvider $payment = null): Response
    {
        /** @var DriverContract|BaseDriver $driver */
        if (!$driver = $this->factory->create($gateway)) {
            throw new PurchaseException("Driver {$gateway->getDriver()} not found");
        }

        try {
            /** @var CompleteRequest $complete */
            $complete = $driver->complete($request, $payment);

            if ($complete->getStatus() === IStatusContractDataProvider::ACCEPT) {
                event(new PurchaseComplete($complete));

                return $driver->success($request);
            }

            if ($complete->getStatus() === IStatusContractDataProvider::PROCESS && method_exists($driver, 'process')) {
                return $driver->process($request);
            }

            event(new PurchaseFailed($request));

            return $driver->failed($request);

        } catch (InvalidResponseException $exception) {
            event(new PurchaseFailed($request, $exception));

            return $driver->failed($request, $exception->getMessage());
        }
    }

}
