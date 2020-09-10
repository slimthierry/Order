<?php

namespace App\Services\Contracts;

use App\Services\Contracts\Data\IDataProvider;
use Illuminate\Http\Request;


/**
 * Interface IPaymentDataProvider
 * @package App\Services\Contracts
 */

interface IPaymentDataProvider extends IDataProvider
{
        // /**
        //  * @return int
        //  */
        // public function getInvoiceId(): int;

        // /**
        //  * @return float
        //  */
        // public function getAmount(): float;

        // /**
        //  * @return int
        //  */
        // public function getCurrencyId(): int;

        public function purchase
        (
            IPaymentContractDataProvider $contract,
            IPaymentDataProvider $payment = null
        );

        public function complete
        (
            IGatewayContractDataProvider $gateway,
            Request $request,
            IPaymentDataProvider $payment = null
        );

}
