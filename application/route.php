<?php
/**
 * 路由中心
 */

use think\Route;

###################API模块路由########################
Route::group(['namespace'=>'api'],function(){
    Route::get('login','index/login');
    Route::get('user/order/list','user/user_order');
});
######################################################
