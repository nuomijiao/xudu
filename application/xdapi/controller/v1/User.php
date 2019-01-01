<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/28
 * Time: 20:28
 */

namespace app\xdapi\controller\v1;


use app\lib\exception\ParameterException;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;
use app\xdapi\controller\BaseController;
use app\xdapi\model\WhActCollect;
use app\xdapi\model\WhActOrder;
use app\xdapi\model\WhFeedback;
use app\xdapi\model\WhFriendsApply;
use app\xdapi\model\WhMemberGrade;
use app\xdapi\model\WhUser;
use app\xdapi\service\Picture;
use app\xdapi\service\Token;
use app\xdapi\validate\FeedbackNew;
use app\xdapi\validate\PagingParameter;
use app\xdapi\validate\UserInfo;

class User extends BaseController
{
    public function getUserInfo()
    {
        $uid = Token::getCurrentUid();
        $userInfo = WhUser::get($uid);
        if (!$userInfo) {
            throw new UserException();
        }
        if ($userInfo->member_end_time == 0 || $userInfo->member_end_time <= time()) {
            $userInfo->is_member = 0;
        } elseif ($userInfo->member_end_time > time()) {
            $userInfo->is_membet = 1;
        }
        $userInfo->collect = WhActCollect::getCollectCount($uid);
        $userInfo->apply = WhFriendsApply::getApplyCount($uid);
        return $this->xdreturn($userInfo);
    }

    public function modifyHeadImg()
    {
        $uid = Token::getCurrentUid();
        $head_img = $this->request->file('head_img');
        $origion_img = WhUser::where('id', '=', $uid)->value('head_img');

        $data = Picture::uploadImg($head_img, 'head_img');

        $user = WhUser::update(['id'=>$uid, 'head_img'=>$data['url']]);
        if ($user) {
            if ($origion_img != '/assets/img/user_head.png' && $origion_img != $data['url']) {
                unlink(ROOT_PATH.'public'.DS.$origion_img);
            }
            return $this->xdreturn(['head_img' => $user->head_img]);
        }
    }

    public function saveInfo()
    {
        $validate = new UserInfo();
        $request = $validate->goCheck();
        $uid = Token::getCurrentUid();
        $dataArray = $validate->getDataByRule($request->post());
        $dataArray['sign'] = $request->param('sign');
        WhUser::where('id', '=', $uid)->update($dataArray);
        throw new SuccessMessage([
            'msg' => '修改成功'
        ]);
    }


    public function feedback()
    {
        $request = (new FeedbackNew())->goCheck();
        $uid = Token::getCurrentUid();
        $content = $request->param('content');
        WhFeedback::create([
            'user_id' => $uid,
            'content' => $content,
        ]);
        throw new SuccessMessage([
           'msg' => '意见反馈成功',
        ]);
    }

    public function memberInfo()
    {
        $uid = Token::getCurrentUid();
        $memberInfo = WhMemberGrade::get(1);
        $power = unserialize($memberInfo->power);
        return $this->xdreturn($power);
    }

    public function getMyTrip($page = 1, $size = 10)
    {
        (new PagingParameter())->goCheck();
        $uid = Token::getCurrentUid();
        $pagingTrip = WhActOrder::getPayOrder($uid, $page, $size);
        if ($pagingTrip->isEmpty()) {
            throw new UserException([
                'msg' => '我的行程已见底线',
                'errorCode' => 50009,
            ]);
        }

        $newTrip = $pagingTrip->toArray()['data'];
        foreach ($newTrip as $key => &$value) {
            $value['act_snap'] = json_decode($value['act_snap'], true);
        }

        return json([
            'error_code' => 'Success',
            'data' => $newTrip,
            'current_page' => $pagingTrip->getCurrentPage(),
        ]);

    }
}