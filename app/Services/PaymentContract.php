<?php
namespace Siqwell\Payment\Contracts;

use App\Services\Contracts\IGatewayContractDataProvider;

/**
 * Interface PaymentContract
 * @package Siqwell\Payment\Contracts
 */
interface PaymentContract
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
     * @return GatewayContract
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











//     public function add_to_cart($userId,$productId,$addOrRemove)
//     {

//         try
//         {

//         $count = AppUser::where('user_id', $userId)->count();
//         if($count==0){
//             return Response::json(
//                 array(
//                     'Success' =>  false,
//                     'message'   =>  "User Not Found"
//                 ), 404);
//         }
//             $order=Order::where(['user_id'=> $userId, 'order_status'=>'cart' ])->get('order_id');
//             if($order->isEmpty()){
//                 $order=new Order(['order_id'=>null,'user_id'=>$userId,'order_status'=>'cart']);
//                 $order->save();
//                 $order=Order::where(['user_id'=> $userId, 'order_status'=>'cart' ])->get('order_id');

//             }


//             $cart_item=Order_Item::where(['order_id'=>$order->first()->order_id,'product_id'=>$productId])->count();
//             if($cart_item==0 && $addOrRemove=='incr')
//             {
//                 $cart_item=new Order_Item(['order_item_id'=>null,'order_id'=>$order->first()->order_id,'product_id'=>$productId,'quantity'=>1]);
//                 $cart_item->save();
//             }else if($cart_item!=0 && $addOrRemove=='incr'){
//                 $cart_item=Order_Item::where(['order_id'=>$order->first()->order_id,'product_id'=>$productId])->increment('quantity');

//             }
//             else if($cart_item!=0 && $addOrRemove=='decr'){
//                 $cart_item=Order_Item::where(['order_id'=>$order->first()->order_id,'product_id'=>$productId])->decrement('quantity');

//             }
//             else if($cart_item!=0 && $addOrRemove=='remove'){
//                 $cart_item=Order_Item::where(['order_id'=>$order->first()->order_id,'product_id'=>$productId])->delete();

//             }
//             else{
//                 return Response::json(
//                     array(
//                         'Success' =>  false,
//                         'message'   =>  "Bad Request",
//                     ), 405);
//             }
//             return Response::json(
//                 array(
//                     'Success' =>  true,
//                     'message'   =>  "Cart Updated Successfully",
//                 ), 200);

//     }catch(Exception $e){
//         return Response::json(
//             array(
//                 'Success' =>  false,
//                 'message'   =>  "Something Went Wrong!"
//             ), 500);
//     }
//     }

// public function cart_items($userId)
// {
//     //
//     try{
//     $count = AppUser::where('user_id', $userId)->count();
//         if($count==0){
//             return Response::json(
//                 array(
//                     'Success' =>  false,
//                     'message'   =>  "User Not Found"
//                 ), 404);
//         }
//         $order=Order::where(['user_id'=> $userId, 'order_status'=>'cart' ])->get('order_id');
//         if($order->isEmpty()){
//             return Response::json(
//                 array(
//                     'Success' =>  true,
//                     'data'=>[],
//                     'message'   =>  "No Item in Cart"
//                 ), 200);
//         }
//         $cart_items=Order_Item::where(['order_id'=>$order->first()->order_id])->pluck('product_id');
//         if($cart_items->isEmpty()){
//             $data=[];
//         }
//         else{
//             //return $cart_items;
//             $results=Product::find($cart_items);
//             $sub_total=0;
//             $discount=0;
//             foreach($results as $result)
//             {
//                 $result['image_url']=url('/')."/product_images/".$result['image_url'];
//                 $sub_total+=$result['price'];
//             }
//             return Response::json(
//                 array(
//                     'Success' =>  true,
//                     'items'=>$results,
//                     'sub_total'=>$sub_total,
//                     'discount'=>$discount,
//                     'total'=>$sub_total-$discount,
//                     'message'   =>  "Items in Cart"
//                 ), 200);
//         }

//     }catch(Exception $e){
//         return Response::json(
//             array(
//                 'Success' =>  false,
//                 'message'   =>  "Something Went Wrong!"
//             ), 500);
//     }


// }

// public function checkout($userId,$addressId=null)
// {
// try{
//     if(!$addressId)
//     {
//         $addressId=Address::where(['user_id'=>$userId,'address_type'=>'primary'])->pluck('address_id')->first();
//         if($addressId){
//             //$order=Order::where(['user_id'=> $userId, 'order_status'=>'cart' ])->update(['address_id'=>$addressId]);
//         }
//     }
// $count = AppUser::where('user_id', $userId)->count();
//         if($count==0){
//             return Response::json(
//                 array(
//                     'Success' =>  false,
//                     'message'   =>  "User Not Found"
//                 ), 404);
//         }

//         $order=Order::where(['user_id'=> $userId, 'order_status'=>'cart' ])->update(['order_status'=>'ordered','address_id'=>$addressId]);
//         if($order==1){
//             return Response::json(
//                 array(
//                     'Success' =>  true,
//                     'message'   =>  "Order Placed"
//                 ), 200);
//         }
//         else{
//             return Response::json(
//                 array(
//                     'Success' =>  false,
//                     'message'   =>  "No item in cart"
//                 ), 500);
//         }

// }catch(Exception $e){
// return Response::json(
//     array(
//         'Success' =>  false,
//         'message'   =>  "Something went wrong!"
//     ), 500);
// }
// }
}
