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
    Route::any('admin/log','AdminController@log');
    Route::any('password','AdminController@password');

    Route::get('inventory/all', 'InventoryController@all')->name('inventory.all');
    Route::get('product/sku/{id}', 'ProductController@sku')->name('product.sku');
    Route::get('product/sku_edit/{id}', 'ProductController@sku_edit')->name('product.sku_edit');
    Route::post('product/sku_update/{id}', 'ProductController@sku_update')->name('product.sku_update');
    Route::get('purchase_order/show_goods','PurchaseOrderController@show_goods');
    Route::post('purchase_order/check/{id}', 'PurchaseOrderController@check')->name('purchase_order.check');
    Route::post('purchase_order/time/{id}', 'PurchaseOrderController@time')->name('purchase_order.time');
    Route::post('purchase_warehouse/check/{id}', 'PurchaseWarehouseController@check')->name('purchase_warehouse.check');
    Route::post('purchase_warehouse/add/{id}', 'PurchaseWarehouseController@add')->name('purchase_warehouse.add');
    Route::post('inventory/{id}/goods_position', 'InventoryController@goods_position')->name('inventory.goodsPosition');


    Route::get('order/list','OrderController@list')->name('order.list');
    Route::get('order/import','OrderController@import')->name('order.import');
    Route::post('order/match','OrderController@match')->name('order.match');
    Route::post('order/create_order_pool','OrderController@createOrderPool');
    Route::any('order/order_pool','OrderController@orderPool');
    Route::post('warehouse_ex/create_ex','WarehouseExController@createEx');

    Route::resource('admin','AdminController');
    Route::resource('category','CategoryController');
    Route::resource('type','TypeController');
    Route::resource('brand','BrandController');
    Route::resource('product_unit','ProductUnitController');
    Route::resource('supplier','SupplierController');
    Route::resource('attribute','AttributeController');
    Route::resource('attribute_value','AttributeValueController');
    Route::resource('product','ProductController');
    Route::resource('product_goods','ProductGoodsController');
    Route::resource('purchase_pool','PurchasePoolController');
    Route::resource('purchase_order','PurchaseOrderController');
    Route::resource('purchase_warehouse','PurchaseWarehouseController');
    Route::resource('inventory','InventoryController');
    Route::resource('inventory_check','InventoryCheckController');
    Route::resource('inventory_transfer','InventoryTransferController');
    Route::resource('order', 'OrderController');
    Route::resource('warehouse','WarehouseController');
    Route::resource('warehouse_ex', 'WarehouseExController');
    Route::resource('warehouse_out', 'WarehouseOutController');
    Route::resource('warehouse_pick', 'WarehousePickController');



    Route::get('data/get_admin','DataController@get_admin');
    Route::get('data/get_attr','DataController@get_attr');
    Route::post('uploader/pic_upload','UploaderController@picUpload');  //图片异步上传
    Route::post('uploader/upload_data','UploaderController@upload_data');  //Excel异步上传

    Route::get('warehouse_pick/export/{id}', 'WarehousePickController@export');  //拣货单导出

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
            $router->put('/orders/{id}/update_remark', 'ShopifyOrderController@update_remark')->name('orders.update_remark');
            $router->get('/export_orders', 'ShopifyOrderController@export')->name('orders.export');
            //审核
            $router->get('/orders/{id}/create_audit','ShopifyOrderController@create_audit');
            $router->put('/orders/{id}/update_audited_at', 'ShopifyOrderController@audit')->name('orders.audit');
            $router->post('/orders/batch_audit', 'ShopifyOrderController@batch_audit')->name('orders.batch_audit');

            //店铺管理
            $router->resource('/shopify_accounts', 'ShopifyAccountController');
            //抓取订单
            $router->post('/create_orders', 'ShopifyAccountController@create_order')->name('shopify_account.create_orders');

        }
    );
    /****END****/

    /******印尼虚拟仓库相关 by tian**/
    //导入入库
    Route::post('import_inventorys', 'InventoryController@import')->name('inventory.import');
    //出库页面
    Route::get('/yn_virtual_out_create', 'InventoryController@yn_virtual_out_create')->name('inventory.yn_virtual_out_create');
    //出库
    Route::post('/yn_virtual_out', 'InventoryController@yn_virtual_out')->name('inventory.yn_virtual_out');
    //入库页面
    Route::get('/yn_virtual_in_create', 'InventoryController@yn_virtual_in_create')->name('inventory.yn_virtual_in_create');
    //入口
    Route::get('/guide', 'InventoryController@guide')->name('inventory.guide');

    /***虚拟仓end */

    /***********印尼仓相关 */
    //待入库列表
    Route::get('/yn_in_create',  'InventoryController@yn_in_create')->name('inventory.yn_in_create');
    Route::get('/yn_out_create',  'InventoryController@yn_out_create')->name('inventory.yn_out_create');
    //入库
    Route::post('/yn_in',  'InventoryController@yn_in')->name('inventory.yn_in');
    Route::post('/yn_out',  'InventoryController@yn_out')->name('inventory.yn_out');

});
