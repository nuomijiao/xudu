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
Route::get('api/:version/banner', 'xdapi/:version.Banner/getBannerList');


//发送注册验证码
Route::post('api/:version/sendsms', 'xdapi/:version.Sms/registerSms');
//发送密码重置验证码
Route::post('api/:version/resetsms', 'xdapi/:version.Sms/resetSms');

//注册
Route::post('api/:version/register', 'xdapi/:version.LogAndReg/register');
//登录
Route::post('api/:version/login', 'xdapi/:version.LogAndReg/login');
//修改密码
Route::post('api/:version/resetpwd', 'xdapi/:version.LogAndReg/resetPwd');

//上传动态图片
Route::post('api/:version/addmomentimg', 'xdapi/:version.Moment/addMomentImg');
//发布动态
Route::post('api/:version/addmoment', 'xdapi/:version.Moment/addMoment');
//获取热门动态
Route::get('api/:version/hotmoment', 'xdapi/:version.Moment/getHot');
//获取关注动态
Route::get('api/:version/followmoment', 'xdapi/:version.Moment/getFollow');
//动态点赞
Route::post('api/:version/zanmoment', 'xdapi/:version.Moment/clickZan');
//获取动态详情
Route::get('api/:version/momentdetail/:id', 'xdapi/:version.Moment/getCommentDetail');
//评论动态
Route::post('api/:version/commentmoment', 'xdapi/:version.Moment/comment');
//回复评论
Route::post('api/:version/replycomment', 'xdapi/:version.Moment/replyComment');
//我的动态
Route::get('api/:version/mymomment', 'xdapi/:version.Moment/myMomment');
//获取作者信息
Route::get('api/:version/ownerinfo/:id', 'xdapi/:version.Moment/ownerInfo');
//获取别人所有动态
Route::get('api/:version/usermoment', 'xdapi/:version.Moment/userMoment');


//申请添加好友
Route::post('api/:version/applyfriend', 'xdapi/:version.Friends/apply');
//获取好友申请列表
Route::get('api/:version/getfriendapplylist', 'xdapi/:version.Friends/getApplyList');
//修改好友申请状态
Route::post('api/:version/applystatus', 'xdapi/:version.Friends/updateApplyStatus');
//获取好友列表
Route::get('api/:version/getfriendlist', 'xdapi/:version.Friends/getList');
//发送消息
Route::post('api/:version/chat', 'xdapi/:version.Friends/chat');
//获取消息列表
Route::get('api/:version/getnewslist', 'xdapi/:version.Friends/getNewsList');



//获取热门活动
Route::get('api/:version/hotactivity', 'xdapi/:version.Activity/getHot');
//获取活动详情
Route::get('api/:version/activitydetail/:id', 'xdapi/:version.Activity/getDetail');
//获取分类下的活动
Route::post('api/:version/activitybycat', 'xdapi/:version.Activity/getActByCat');
//获取收藏的活动列表
Route::get('api/:version/collectlist', 'xdapi/:version.Activity/getCollectList');
//收藏活动
Route::get('api/:version/collectact/:id', 'xdapi/:version.Activity/collect');



//添加许愿
Route::post('api/:version/addwish', 'xdapi/:version.Wishes/addWish');
//我的愿望
Route::get('api/:version/mywish', 'xdapi/:version.Wishes/myWish');
//愿望列表
Route::get('api/:version/wisheslist', 'xdapi/:version.Wishes/getWishes');


//确定活动订单
Route::get('api/:version/sureactorder/:id', 'xdapi/:version.Order/sureActOrder');
//生成活动订单
Route::post('api/:version/makeactorder', 'xdapi/:version.Order/makeActOrder');
//获取订单详情
Route::get('api/:version/getactorderdetail', 'xdapi/:version.Order/getActOrderDetail');
//申请取消订单，判断该活动是否已过。
Route::post('api/:version/cancelactorder', 'xdapi/:version.Order/cancelActOrder');
//活动订单回调
Route::post('api/:version/actordernotify', 'xdapi/:version.Order/notifyActOrder');

//确定会员订单
Route::get('api/:version/surememorder/:id', 'xdapi/:version.Order/sureMemOrder');
//生成会员订单
Route::post('api/:version/makememorder', 'xdapi/:version.Order/makeMemOrder');
//会员订单回调
Route::post('api/:version/memordernotify', 'xdapi/:version.Order/notifyMemOrder');



//获取用户信息
Route::get('api/:version/getuserinfo', 'xdapi/:version.User/getUserInfo');
//修改用户头像
Route::post('api/:version/modifyheadimg', 'xdapi/:version.User/modifyHeadImg');
//修改用户信息
Route::post('api/:version/saveinfo', 'xdapi/:version.User/saveInfo');
//反馈意见
Route::post('api/:version/feedback', 'xdapi/:version.User/feedback');
//会员购买信息
Route::get('api/:version/memberinfo', 'xdapi/:version.User/memberInfo');
//获取我的行程，支付的活动订单
Route::get('api/:version/mytrip', 'xdapi/:version.User/getMyTrip');



//获取城市地址拼音排序列表
Route::get('api/:version/getcitylist', 'xdapi/:version.Character/getCityList');
///获取城市的id
Route::post('api/:version/getcityid', 'xdapi/:version.Character/GetCityId');
//获取省列表
Route::get('api/:version/getprovince', 'xdapi/:version.Character/getProvince');
//获取联动市
Route::get('api/:version/getcitybyprovince', 'xdapi/:version.Character/getCityByProvince');
//获取联动区
Route::get('api/:version/getdistrictbycity', 'xdapi/:version.Character/getDistrictByCity');

//获取系统消息
Route::get('api/:version/getsystemnews', 'xdapi/:version.SystemNews/getSystemNews');

