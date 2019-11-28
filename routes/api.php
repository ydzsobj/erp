<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->post('login', 'App\Http\Api\Auth\LoginController@login');
    $api->post('register', 'App\Http\Api\Auth\RegisterController@register');
    $api->get('refresh','App\Http\Api\UsersController@refresh');
    $api->group(['namespace'=>'App\Http\Controllers\Api'],function($api){
        $api->get('/',function (){
            echo "myApi";
        });
        $api->get('logout','App\Http\Api\Auth\LoginController@logout');
        $api->resource('user','App\Http\Api\UsersController');

        $api->resource('/category','CategoryController', ['only' => ['index']]);
        $api->resource('/type','TypeController', ['only' => ['index']]);
        $api->resource('/brand','BrandController', ['only' => ['index']]);
        $api->resource('/product_unit','ProductUnitController', ['only' => ['index']]);
        $api->resource('/supplier','SupplierController', ['only' => ['index']]);
        $api->resource('/attribute','AttributeController', ['only' => ['index']]);
        $api->resource('/attribute_value','AttributeValueController', ['only' => ['index']]);
        $api->resource('/product','ProductController', ['only' => ['index','show']]);
        $api->resource('/product_goods','ProductGoodsController', ['only' => ['index']]);
        $api->resource('/purchase_pool','PurchasePoolController', ['only' => ['index','show']]);
        $api->resource('/purchase_order','PurchaseOrderController', ['only' => ['index']]);
        $api->resource('/purchase_warehouse','PurchaseWarehouseController', ['only' => ['index']]);
        $api->resource('/inventory','InventoryController', ['only' => ['index']]);
        $api->resource('/inventory_check','InventoryCheckController', ['only' => ['index']]);
        $api->resource('/inventory_transfer','InventoryTransferController', ['only' => ['index']]);
        $api->resource('/order','OrderController', ['only' => ['index']]);
        $api->resource('/warehouse','WarehouseController', ['only' => ['index']]);
        $api->resource('/warehouse_ex','WarehouseExController', ['only' => ['index']]);
        $api->resource('/warehouse_pick','WarehousePickController', ['only' => ['index']]);


        $api->get('/product/sku/{id}','ProductController@sku');
        $api->get('/product/get_sku/{id}','ProductController@get_sku');
        $api->get('/purchase_order/goods/{id}','PurchaseOrderController@goods');
        $api->get('/purchase_warehouse/goods/{id}','PurchaseWarehouseController@goods');
        $api->get('/inventory/goods/{id}','InventoryController@goods');
        $api->get('/attribute/get_attr_value/{id}','AttributeController@get_attr_value');

        /******订单相关****/
        // $api->get('/orders','OrderController@index')->name('api.orders.index');
        $api->get('/orders','ShopifyOrderController@index')->name('api.orders.index');
        $api->get('/orders/{id}','ShopifyOrderController@show')->name('api.orders.show');
        $api->get('/shopify_accounts','ShopifyAccountController@index')->name('api.shopify_accounts.index');


    });




});
