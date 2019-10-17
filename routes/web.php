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



    Route::get('data/get_admin','DataController@get_admin');
    Route::get('data/get_attr','DataController@get_attr');
    Route::post('uploader/pic_upload','UploaderController@picUpload');  //图片异步上传

});
