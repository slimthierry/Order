<?php

namespace App\Services;

use App\Models\Deliverance;
use App\Traits\DataProvider;
use Illuminate\Support\Facades\Auth;
use App\Services\Contracts\IDeliverancesDataProvider;

class DeliverancesDataProvider implements IDeliverancesDataProvider
{
    use DataProvider;

    public function __construct()
    {

    }

    public function getOpenDeliverances()
    {
        $userMember = Auth::user();
        $society = $userMember->findSocietyByUser();
        if ($userMember->type == "society") {
            if (!empty($society)) {
                return Deliverance::where('society_id', $society->id)
                    ->whereIn('status_id', [1, 5])
                    ->with(
                        [
                            'products', 'address', 'address.region','address.district', 'address.city',
                            'payment_method', 'client', 'society', 'products.slug', 'products.price',
                            'products.quantity', 'products.category'
                        ]
                    )->orderBy('created_at', 'desc')
                    ->get();
            }
        }
        if ($userMember->type == "admin") {
            return Deliverance::with(
                [
                    'products', 'address', 'address.region','address.district', 'address.city',
                    'payment_method', 'client', 'society', 'products.slug', 'products.price',
                    'products.quantity', 'products.category'
                ]
            )->whereIn('status_id', [1, 5])
             ->orderBy('created_at', 'desc')
             ->get();
        }
    }

    public function getClosedDeliverances()
    {
        $userMember = Auth::user();
        $society = $userMember->findSocietyByUser();
        if ($userMember->type == "society") {
            if (!empty($society)) {
                return Deliverance::where('society_id', $society->id)
                    ->where('status_id', 2)
                    ->with(
                        [
                            'products', 'address', 'address.region','address.district', 'address.city',
                            'payment_method', 'client', 'society', 'products.slug', 'products.price',
                            'products.quantity', 'products.category'
                        ]
                    )->orderBy('created_at', 'desc')
                    ->paginate(10);
            }
        }
        return Deliverance::with(
            [
                'products', 'address', 'address.region','address.district', 'address.city',
                'payment_method', 'client', 'society', 'products.slug', 'products.price',
                'products.quantity', 'products.category'

            ])->where('status_id', 2)
             ->orderBy('created_at', 'desc')
             ->paginate(10);
    }

    public function derliverancesByClient($id)
    {
        return Deliverance::where('client_id', $id)
                ->with(
                    [
                'products', 'address', 'address.region','address.district', 'address.city',
                'payment_method', 'client', 'society', 'products.slug', 'products.price',
                'products.quantity', 'products.category'

                    ]
                )->get();
    }


}
