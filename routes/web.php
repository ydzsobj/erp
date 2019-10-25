<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/admins/login','Erp\LoginController@index')->name('login');
Route::post('/admins/login','Erp\LoginController@store');
Route::group(['prefix'=>'admins','middleware'=>'auth:admin','namespace'=>'Erp'],function (){
    Route::get('logout','LoginController@logout')->name('logout');
    Route::get('index','IndexController@index');
    Route::get('home_page','IndexController@homePage');
    Route::get('admin_info','AdminController@adminInfo');
    Route::any('password','AdminController@password');

    Route::get('product/sku/{id}', 'ProductController@sku')->name('product.sku');
    Route::get('purchase_order/show_goods','PurchaseOrderController@show_goods');



    Route::resource('admin','AdminController');
    Route::resource('category','CategoryController');
    Route::resource('type','TypeController');
    Route::resource('brand','BrandController');
    Route::resource('product_unit','ProductUnitController');
    Route::resource('supplier','SupplierController');
    Route::resource('warehouse','WarehouseController');
    Route::resource('attribute','AttributeController');
    Route::resource('attribute_value','AttributeValueController');
    Route::resource('product','ProductController');
    Route::resource('product_goods','ProductGoodsController');
    Route::resource('purchase_order','PurchaseOrderController');
    Route::resource('purchase_warehouse','PurchaseWarehouseController');



    Route::get('data/get_admin','DataController@get_admin');
    Route::get('data/get_attr','DataController@get_attr');
    Route::post('uploader/pic_upload','UploaderController@picUpload');  //图片异步上传


     /****订单相关****/
    // Route::group(['middleware' => [] ],
    //     function($router){
    //         $router->resource('/orders', 'OrderController');
    //         $router->get('/create_import', 'OrderController@create_import')->name('orders.create_import');
    //         $router->post('/import_orders', 'OrderController@import')->name('orders.import');
    //         //审核
    //         $router->post('/orders/update_audited_at/{id}', 'OrderController@audit')->name('orders.audit');
    //         $router->post('/orders/batch_audit', 'OrderController@batch_audit')->name('orders.batch_audit');
    //     }
    // );

    //shopify订单相关
    Route::group(['middleware' => [] ],
        function($router){
            $router->resource('/orders', 'ShopifyOrderController');
            // $router->get('/create_import', 'ShopifyOrderController@create_import')->name('orders.create_import');
            $router->post('/export_orders', 'ShopifyOrderController@export')->name('orders.export');
            //审核
            $router->post('/orders/update_audited_at/{id}', 'ShopifyOrderController@audit')->name('orders.audit');
            $router->post('/orders/batch_audit', 'ShopifyOrderController@batch_audit')->name('orders.batch_audit');

            //店铺管理
            $router->resource('/shopify_accounts', 'ShopifyAccountController');
            //抓取订单
            $router->post('/create_orders', 'ShopifyAccountController@create_order')->name('shopify_account.create_orders');

    }
);
 /****END****/
});
