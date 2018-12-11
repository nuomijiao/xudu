<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/11
 * Time: 15:29
 */

namespace app\xdapi\controller\v1;


use app\lib\enum\FriendsApplyStatus;
use app\lib\exception\FriendsException;
use app\lib\exception\SuccessMessage;
use app\xdapi\controller\BaseController;
use app\xdapi\model\WhFriendsApply;
use app\xdapi\service\Token;
use app\xdapi\validate\IDMustBePositiveInt;

class Friends extends BaseController
{
    public function apply($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();
        $uid = Token::getCurrentUid();
        $apply = WhFriendsApply::checkApplyExist($uid, $id);
        if (!$apply || $apply->status == FriendsApplyStatus::Deny) {
            WhFriendsApply::create([
                'my_id' => $uid,
                'friend_id' => $id,
                'status' => 0,
            ]);
        } else {
            if ($apply->status == FriendsApplyStatus::Pass) {
                throw new FriendsException([
                    'msg' => 'TA已经是你好友',
                    'errorCode' => 80002,
                ]);
            }
            WhFriendsApply::update(['id' => $apply->id, 'update_time' => time()]);
        }
        throw new SuccessMessage([
            'msg' => '发送申请成功',
        ]);
    }

    public function getApplyList()
    {
        $uid = Token::getCurrentUid();
        $applyList = WhFriendsApply::getList($uid);
        if ($applyList->isEmpty()) {
            throw new FriendsException([
                'msg' => '好友申请为空',
                'errorCode' => 80001,
            ]);
        }

    }

    public function getList()
    {
        $uid = Token::getCurrentUid();
    }
}