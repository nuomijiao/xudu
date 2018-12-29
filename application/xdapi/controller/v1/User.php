<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/28
 * Time: 20:28
 */

namespace app\xdapi\controller\v1;


use app\lib\exception\ParameterException;
use app\lib\exception\UserException;
use app\xdapi\controller\BaseController;
use app\xdapi\model\WhActCollect;
use app\xdapi\model\WhFriendsApply;
use app\xdapi\model\WhUser;
use app\xdapi\service\Picture;
use app\xdapi\service\Token;
use app\xdapi\validate\PictureNew;

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

        if (!empty($head_img)) {

            if(!Picture::checkImg($head_img)) {
                throw new ParameterException([
                    'msg' => '上传图片参数错误',
                ]);
            }
        }

        $data = Picture::uploadImg($head_img, 'head_img');
        $origion_img = WhUser::where('id', '=', $uid)->value('head_img');
        $user = WhUser::update(['id'=>$uid, 'head_img'=>$data['head_img']]);
        if ($user) {
            if ($origion_img != '/assets/img/user_head.png') {
                unlink(ROOT_PATH.'public'.DS.$origion_img);
            }
            return $this->xdreturn(['head_img' => $user->head_img]);
        }
    }
}