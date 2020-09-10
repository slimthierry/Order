<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::apiResources([

    'categories'                            =>'CategoriesController',
    'products'                              => 'ProductController',
    'addresses'                             => 'AddressController',
    'countries'                             => 'CountryController',
    'orders'                                  => 'OrderController',
    'payment-methods'=> 'PaymentMethodController',
    'employes' => 'EmployeController',
    'customer' => 'CustomerController',

    'addresses/{address}/shipping'=> 'AddressShippingController@action',

    'employes/{employe}/ratings' => 'RatingController'
]);
// Route::resource('categories', 'CategoriesController');



Route::resource('cart', 'Cart\CartController', [
  'parameters' => [
    'cart' => 'productVariation'
  ]
]);

Route::group(['prefix' => 'auth'], function () {
  Route::post('login', 'Auth\LoginController@action');
  Route::post('register', 'Auth\RegisterController@action');
  Route::get('me', 'Auth\MeController@action');
});


// Register Routes
Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::post('logout', 'AuthController@logout');


Route::middleware('jwt.auth')->get('me', function(Request $request) {
    return auth()->user();
});



// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });





