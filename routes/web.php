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
Route::group(['prefix'=>'admins','middleware'=>['auth:admin','SsoMiddleware'],'namespace'=>'Erp'],function (){
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
    Route::post('purchase_order/note/{id}', 'PurchaseOrderController@note')->name('purchase_order.note');
    Route::post('purchase_order/{id}/code', 'PurchaseOrderController@code')->name('purchase_order.code');
    Route::get('purchase_order/{id}/look', 'PurchaseOrderController@look')->name('purchase_order.look');
    Route::post('purchase_warehouse/check/{id}', 'PurchaseWarehouseController@check')->name('purchase_warehouse.check');
    Route::post('purchase_warehouse/in/{id}', 'PurchaseWarehouseController@in')->name('purchase_warehouse.in');
    Route::post('purchase_warehouse/add', 'PurchaseWarehouseController@add')->name('purchase_warehouse.add');
    Route::post('purchase_warehouse_info/problem/{id}', 'PurchaseWarehouseInfoController@problem')->name('purchase_warehouse_info.problem');
    Route::post('inventory/{id}/goods_position', 'InventoryController@goods_position')->name('inventory.goodsPosition');
    Route::post('warehouse_pick/check', 'WarehousePickController@check')->name('warehouse_pick.check');
    Route::post('warehouse_pick/problem', 'WarehousePickController@problem')->name('warehouse_pick.problem');
    Route::post('warehouse_out/out', 'WarehouseOutController@out')->name('warehouse_out.out');
    Route::post('inventory_check/check/{id}', 'InventoryCheckController@check')->name('inventory_check.check');
    Route::post('inventory_check/change/{id}', 'InventoryCheckController@change')->name('inventory_check.change');
    Route::post('inventory_check/all', 'InventoryCheckController@all')->name('inventory_check.all');
    Route::post('warehouse_in/in/{id}', 'WarehouseInController@in')->name('warehouse_in.in');


    Route::get('order/list','OrderController@list')->name('order.list');
    Route::get('order/import','OrderController@import')->name('order.import');
    Route::post('order/match','OrderController@match')->name('order.match');
    Route::post('order/create_order_pool','OrderController@createOrderPool');
    Route::any('order/order_pool','OrderController@orderPool');
    Route::post('warehouse_ex/create_ex','WarehouseExController@createEx');
    Route::get('inventory_check/import/{id}','InventoryCheckController@import')->name('inventory_check.import');

    /* 问题处理 */
    Route::post('problem/check/{id}', 'ProblemController@check')->name('problem.check');


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
    Route::resource('purchase_warehouse_info','PurchaseWarehouseInfoController');
    Route::resource('inventory','InventoryController');
    Route::resource('inventory_check','InventoryCheckController');
    Route::resource('inventory_transfer','InventoryTransferController');
    Route::resource('order', 'OrderController');
    Route::resource('warehouse_in', 'WarehouseInController');
    Route::resource('warehouse','WarehouseController');
    Route::resource('warehouse_ex', 'WarehouseExController');
    Route::resource('warehouse_out', 'WarehouseOutController');
    Route::resource('warehouse_pick', 'WarehousePickController');
    Route::resource('problem', 'ProblemController');



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
    //标记问题件
    Route::put('/inventory/{id}/update_status', 'InventoryController@update_status')->name('inventory.update_status');
    //问题件列表
    Route::get('/inventory_problems_create', 'InventoryController@problems_create')->name('inventory.problems_create');

    /***虚拟仓end */

    /***********印尼仓相关 */
    //待入库列表
    Route::get('/yn_in_create',  'InventoryController@yn_in_create')->name('inventory.yn_in_create');
    Route::get('/yn_out_create',  'InventoryController@yn_out_create')->name('inventory.yn_out_create');
    //入库
    Route::post('/yn_in',  'InventoryController@yn_in')->name('inventory.yn_in');
    //出库
    Route::post('/yn_out',  'InventoryController@yn_out')->name('inventory.yn_out');

    //更新仓位
    Route::post('/inventory/{id}/update_fields', 'InventoryController@update_fields')->name('inventory.update_fields');
    //订单出库导出
    Route::get('/order_out_export', 'InventoryController@order_out_export')->name('inventory.order_out_export');
    //设置订单异常
    Route::put('/set_order_status', 'InventoryController@set_order_status')->name('inventory.set_order_status');


});
