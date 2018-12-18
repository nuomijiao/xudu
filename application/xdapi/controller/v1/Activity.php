<?php


namespace app\xdapi\controller\v1;

use app\lib\exception\ActivityException;
use app\xdapi\controller\BaseController;
use app\xdapi\model\WhActCollect;
use app\xdapi\model\WhActivity;
use app\xdapi\service\Token;
use app\xdapi\validate\IDMustBePositiveInt;
use app\xdapi\validate\PagingParameter;


class Activity extends BaseController
{

    //获取热门动态
    public function getHot($page = 1, $size = 10)
    {
        (new PagingParameter())->goCheck();
        $pagingHotAct = WhActivity::getHotActivity($page, $size);
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
        $banner_img = explode(',', $activity->benner_image);
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
        } elseif ($collect && $collect->delete_time = 0) {
            //取消收藏, delete_time = time();
            $isCollect = WhActCollect::update(['id' => $collect->id, 'delete_time' => time()]);
        } elseif (!$collect) {
            //收藏 delete_time = 0
            $isCollect = WhActCollect::create([
                'act_id' => $id,
                'user_id' => $uid,
                'delete_time' => time()
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
    public function getActByCat($id = '', $page = 1, $size = 10)
    {
        (new IDMustBePositiveInt())->goCheck();
        (new PagingParameter())->goCheck();
        $pagingAct = WhActivity::getActivityByCat($id, $page, $size);
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


}