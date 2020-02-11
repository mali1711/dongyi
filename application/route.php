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

Route::resource('admin/index', 'admin/Index');
Route::resource('admin/order', 'admin/Order');
Route::resource('admin/project', 'admin/Project');
Route::controller('admin/files', 'admin/Files');
Route::resource('admin/staff', 'admin/Staff');
Route::resource('admin/shop', 'admin/Shop');

Route::group('api', function () {
    Route::resource('/staff', 'api/Staff');
    Route::resource('/project', 'api/Project');
    Route::controller('/order', 'api/Order');
    Route::controller('/login', 'api/Login');
    Route::controller('/user', 'api/User');
    Route::controller('/address', 'api/Address');
    Route::controller('/managetime', 'api/ManageTime');//时间管理
    Route::controller('/sms', 'api/Sms');//短信验证
});



