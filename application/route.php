<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------


/*
return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];

*/


use think\Route;

/**
 * 后台登陆
 */
Route::group('admin',function (){
    Route::resource('/login', 'admin/login');
    Route::resource('/index', 'admin/Index');
    Route::resource('/order', 'admin/Order');
    Route::resource('/project', 'admin/Project');
    Route::controller('/files', 'admin/Files');
    Route::resource('/staff', 'admin/Staff');
    Route::resource('/staffManagetime', 'admin/StaffManagetime');
    Route::resource('/shop', 'admin/Shop');
    Route::controller('/user', 'admin/User');
});

/**
 * 用户客户端接口
 */
Route::group('api', function () {
    Route::resource('/staff', 'api/Staff');
    Route::resource('/project', 'api/Project');
    Route::controller('/order', 'api/Order');
    Route::controller('/login', 'api/login');
    Route::controller('/user', 'api/User');
    Route::controller('/address', 'api/Address');
    Route::controller('/managetime', 'api/ManageTime');//时间管理
    Route::controller('/sms', 'api/Sms');//短信验证
    Route::controller('/api', 'api/Pay');//支付
    Route::resource('/shop', 'api/Shop');//支付
    Route::controller('/version', 'api/Version');// 版本信息
});

/**
 * 公共方法
 */
Route::group('common',function (){
    Route::controller('/sms', 'common/Sms');//短信验证
});

/**
 * 技师端接口
 */
Route::group('staff',function (){
    Route::controller('/staff', 'staff/Staff');
    Route::controller('/order', 'staff/Order');
    Route::controller('/timemanage', 'staff/TimeManagement');
});

/**
 * 技师端接口
 */
Route::group('backstage',function (){
    Route::controller('/staff', 'backstage/Staff');
    Route::controller('/order', 'backstage/Order');
    Route::controller('/project', 'backstage/Project');
    Route::controller('/shop', 'backstage/Shop');
    Route::controller('/login', 'backstage/login');
});


