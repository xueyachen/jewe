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

//Route::get('/', function () {
//    return view('welcome');
//});


//登录
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');



//首页
Route::get('/index','IndexController@index');
//注册
Route::get('/reg',function(){
	return view('login.reg');
});
//注册控制器
Route::get('/index/reg','RegController@index');
//短信发送
Route::get('/index/send','RegController@send');
//获取手机号
Route::post('/index/tel','RegController@tel');
//获取邮箱
Route::post('/index/email','RegController@email');
//手机号验证唯一
Route::post('/index/checktel','RegController@checktel');
//邮箱验证唯一
Route::post('/index/checkemaill','RegController@checkemaill');
//注册入库
Route::post('/index/regist','RegController@store');


//登录
Route::get('/login',function(){
	return view('login.login');
});
//登录
Route::post('/index/subtel','LoginController@subtel');


//商品列表
Route::any('/index/goodslist/{id?}','GoodslistController@index');
//商品列表筛选
Route::any('/index/screen','GoodslistController@screen');
//商品列表 展示搜索
//Route::any('/index/goodslist1/{username}','GoodslistController@search');
//商品列表搜索
Route::any('/index/search','GoodslistController@search');
//商品搜索筛选
Route::any('/index/screen1','GoodslistController@screen1');

//商品详情
Route::get('/index/goodspart/{id}','GoodspartController@index');
//商品收藏
Route::any('/index/collect','GoodspartController@collect');

//加入购物车
Route::post('/index/caradd','GoodspartController@caradd');
//购物车列表
Route::get('/index/carlist','CarlistController@index')->middleware('islogin');
//购物车单删
Route::any('/index/goodsdel','CarlistController@goodsdel');
//总价
Route::post('/index/allcount','CarlistController@allcount');
//更改购买数量
Route::post('/index/goodsInfoNum','CarlistController@goodsInfoNum');
//购物车列表 去结算
Route::any('/index/goodspay','CarlistController@goodspay');

//结算商品页面
Route::get('/index/carpay/{id}','CarpayController@index')->middleware('islogin');
//提交订单
Route::any('/index/order','SuccessController@order');
//提交订单视图
Route::get('/index/success/{id}','SuccessController@index');
//个人中心
Route::get('/index/user','UserController@index')->middleware('islogin');
//收货地址添加视图
Route::get('/index/addressadd','AddressController@add');
//收货地址添加
Route::any('/index/addressAdd','AddressController@addressAdd');
//收货地址删除
Route::any('/index/del','AddressController@del');
//收货地址修改
Route::any('/index/edit/{id}','AddressController@edit');
//收货地址修改执行
Route::any('/index/addressupdate','AddressController@update');

//收货地址 内容更新事件
Route::post('/index/change','AddressController@change');
//收货地址列表
Route::get('/index/addresslist','AddressController@index');

//立即支付
Route::any('/index/aipay/{order_no}','SuccessController@aipay');
//支付同步通知
Route::get('/index/async','SuccessController@async');
//支付异步通知
Route::post('/index/notify','SuccessController@notify');

//测试cookie
Route::get('/index/test','GoodspartController@test');
//测试memcache
Route::any('/index/test/{id}','IndexController@test');


//内测1 注册视图
Route::any('/regist',function(){
	return view('regist.regist');
});
//获取手机号唯一
Route::any('index/regist','RegistController@index');
//获取验证码
Route::any('index/sendTel','RegistController@sendTel');
//注册提交
Route::any('index/registSub','RegistController@add');
//登录视图
Route::any('/login1',function(){
	return view('regist.login');
});
//登录提交
Route::any('/index/loginSub','RegistController@loginList');
//登陆成功进用户信息页面
Route::any('/index/lists','RegistController@lists');

//内测2 列表试图
Route::any('/index/gods','GodsController@index');
//删除
Route::any('/index/goodsDel/{id}','GodsController@goodsDel');
//修改
Route::any('/index/goodsEdit/{id}','GodsController@goodsEdit');
//修改执行
Route::any('/index/update/{id}','GodsController@update');
//详情
Route::any('/index/godsPart/{id}','GodsController@godsPart');
//redis测试
Route::any('/index/test/{id}','GodsController@test');

