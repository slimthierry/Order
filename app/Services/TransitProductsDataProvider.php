<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use App\Services\Contracts\ITransitsProductsDataProvider;
use App\Exceptions\TransitProductsNotAllowedException;
use App\Traits\DataProvider;
use App\Services\Contracts\IShipsDataProvider;
use App\Services\Contracts\IProductsDataProvider;
use DB;

class TransitProductsDataProvider implements ITransitsProductsDataProvider
{

    use DataProvider;

    public function __construct(
            ITransitsProductsDataProvider $ITransitsProductsDataProvider,
            IShipsDataProvider $IShipsDataProvider,
            IProductsDataProvider $IProductsDataProvider)
        {
          $this->ITransitsProductsDataProvider = $ITransitsProductsDataProvider;
          $this->IShipsDataProvider = $IShipsDataProvider;
          $this->IProductsDataProvider = $IProductsDataProvider;
        }

    public function create(array $data)
            {
                \DB::beginTransaction();

                try {
                $data['status'] = 0;

            if (isset($data['ship_id']))
             {
                unset($data['ship_id']);
             }

             if (isset($data['ship_code']))
             {
                $ship = $this->shipRepository->findByField('code', $data['ship_code'])->first();
                $data['ship_id'] = $ship->id;
                $ship->userd = 1;
                $ship->save();
                unset($data['ship_code']);
              }

                $items = $data['items'];
                unset($data['items']);

                $order = $this->orderRepository->create($data);
                $total = 0;

                    foreach ($items as $item)
                    {
                        $item['price'] = $this->productRepository->find($item['product_id'])->price;
                        $order->items()->create($item);
                        $total += $item['price'] * $item['qtd'];
                     }
               $order->total = $total;

                        if (isset($ship))
                        {
                            $order->total = $total - $ship->value;
                        }

                $order->save();

                \DB::commit();

                return $order;

                } catch (\Exception $e) {
                \DB::rollback();

                throw $e;
                }
                }

        public function updateStatus($id, $idDeliveryemploye, $status)
            {
                $order = $this->orderRepository->getByIdAndDeliveryemploye($id, $idDeliveryemploye);
                $order->status = $status;

                $order->save();

                return $order;
            }

}
