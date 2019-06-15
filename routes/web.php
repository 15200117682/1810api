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

Route::get("/login","User\UserController@login");//登陆


Route::get("/test/curl2","Test\TestController@curl2");//百度
Route::get("/test/getAccessToken","Test\TestController@getAccessToken");//access
Route::get("/test/menu","Test\TestController@menu");//自定义菜单

Route::get("/test/encryption","Test\TestController@encryption");//测试加密方法
Route::get("/test/no_enc","Test\TestController@noEncryption");//测试非对称加密方法
Route::get("/test/signature","Test\TestController@signature");//签名
Route::get("/test/alipay","Test\TestController@alipay");//测试支付宝支付
Route::get("/test/pay","Test\TestController@pay");//测试支付宝支付

Route::get("/test/index","Test\TestController@index");//页面
Route::post("/test/curl3","Test\TestController@curl3");//调用接口

