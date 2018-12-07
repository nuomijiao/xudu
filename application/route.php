<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//return [
//    //别名配置,别名只能是映射到控制器且访问时必须加上请求的方法
//    '__alias__'   => [
//    ],
//    //变量规则
//    '__pattern__' => [
//    ],
////        域名绑定到模块
////        '__domain__'  => [
////            'admin' => 'admin',
////            'api'   => 'api',
////        ],
//];

use think\Route;

//Route::rule('路由表达式', '路由地址', '请求类型', '路由参数（数组）', '变量控制（数组）');

//首页分类显示
Route::get('api/:version/category', 'xdapi/:version.Category/getCategory');

//首页轮播
Route::get('api/:version/banner', 'xdapi/:version.Banner/getBanner');


//发送验证码
Route::post('api/:version/sendsms', 'xdapi/:version.Sms/registerSms');
//注册
Route::post('api/:version/register', 'xdapi/:version.LogAndReg/register');
//登录
Route::post('api/:version/login', 'xdapi/:version.LogAndReg/login');
