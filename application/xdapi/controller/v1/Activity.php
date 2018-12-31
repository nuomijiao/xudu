<?php


namespace app\xdapi\controller\v1;

use app\lib\enum\ActivityTypeEnum;
use app\lib\exception\ActivityException;
use app\xdapi\controller\BaseController;
use app\xdapi\model\WhActCollect;
use app\xdapi\model\WhActivity;
use app\xdapi\service\Token;
use app\xdapi\validate\ActByCat;
use app\xdapi\validate\CityId;
use app\xdapi\validate\IDMustBePositiveInt;
use app\xdapi\validate\PagingParameter;


class Activity extends BaseController
{

    //获取热门活动
    public function getHot($city_id = 861, $page = 1, $size = 10)
    {
        (new PagingParameter())->goCheck();
        (new CityId())->goCheck();
        $pagingHotAct = WhActivity::getHotActivity($city_id, $page, $size);
        if ($pagingHotAct->isEmpty()) {
            throw new ActivityException([
                'msg' => '热门活动已见底线',
                'errorCode' => 20001,
            ]);
        }
        $data = $pagingHotAct->toArray();
        return json([
            'error_code' => 'Success',
            'data' => $data,
            'current_page' => $pagingHotAct->getCurrentPage(),
        ]);
    }


    //获取活动详情
    public function getDetail($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();
        $uid = Token::getCurrentUid();
        //能判断是否收藏；
        $activity = WhActivity::getActDetail($id, $uid);
        if (!$activity) {
            throw new ActivityException();
        }
        $banner_img = explode(',', $activity->banner_imgs);
        foreach ($banner_img as $key => $value) {
            $banner_img[$key] = config('setting.domain').$value;
        }
        $activity->banner_img = $banner_img;
        return $this->xdreturn($activity);
    }

    //活动收藏 $id 活动id
    public function collect($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();
        $uid = Token::getCurrentUid();
        //判断是否收藏
        $collect = WhActCollect::checkIsCollect($id, $uid);
        if ($collect && $collect->delete_time > 0) {
            //收藏，delete_time = 0
            $isCollect = WhActCollect::update(['id' => $collect->id, 'delete_time' => 0]);
        } elseif ($collect && $collect->delete_time == 0) {
            //取消收藏, delete_time = time();
            $isCollect = WhActCollect::update(['id' => $collect->id, 'delete_time' => time()]);
        } elseif (!$collect) {
            //收藏 delete_time = 0
            $isCollect = WhActCollect::create([
                'act_id' => $id,
                'user_id' => $uid,
                'delete_time' => 0
            ]);
        }
        return $this->xdreturn($isCollect);
    }


    //收藏活动列表
    public function getCollectList($page = 1, $size = 10)
    {
        (new PagingParameter())->goCheck();
        $uid = Token::getCurrentUid();
        $pagingCollectAct = WhActCollect::getCollectActivity($uid, $page, $size);
        if ($pagingCollectAct->isEmpty()) {
            throw new ActivityException([
                'msg' => '收藏活动已见底线',
                'errorCode' => 20003,
            ]);
        }
        $data = $pagingCollectAct->toArray();
        return json([
            'error_code' => 'Success',
            'data' => $data,
            'current_page' => $pagingCollectAct->getCurrentPage(),
        ]);
    }

    //获取分类下的活动列表
    public function getActByCat($city_id = 861, $id = '', $page = 1, $size = 10, $type = ActivityTypeEnum::All, $searchkey = '')
    {
        (new ActByCat())->goCheck();
        $pagingAct = WhActivity::getActivityByCat($city_id, $id, $page, $size, $type, $searchkey);
        if ($pagingAct->isEmpty()) {
            throw new ActivityException([
                'msg' => '分类下活动已见底线',
                'errorCode' => 20002,
            ]);
        }
        $data = $pagingAct->toArray();
        return json([
            'error_code' => 'Success',
            'data' => $data,
            'current_page' => $pagingAct->getCurrentPage(),
        ]);

    }


    //后台用接口
    public function actlist()
    {
        $cat_list = WhActivity::field(['id','act_name'])->select()->toArray();
        return json($cat_list);
    }

}