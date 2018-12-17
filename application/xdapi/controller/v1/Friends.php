<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/11
 * Time: 15:29
 */

namespace app\xdapi\controller\v1;


use app\lib\enum\FriendsApplyStatusEnum;
use app\lib\exception\FriendsException;
use app\lib\exception\SuccessMessage;
use app\xdapi\controller\BaseController;
use app\xdapi\model\WhFriends;
use app\xdapi\model\WhFriendsApply;
use app\xdapi\service\Friends as FriendsService;
use app\xdapi\service\Token;
use app\xdapi\validate\FriendStatus;
use app\xdapi\validate\IDMustBePositiveInt;

class Friends extends BaseController
{
    //申请好友
    public function apply($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();
        $uid = Token::getCurrentUid();
        $apply = WhFriendsApply::checkApplyExist($uid, $id);
        if (!$apply || $apply->status == FriendsApplyStatusEnum::Deny) {
            WhFriendsApply::create([
                'my_id' => $uid,
                'friend_id' => $id,
                'status' => 0,
            ]);
        } else {
            if ($apply->status == FriendsApplyStatusEnum::Pass) {
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

    //改变申请状态，拒绝，通过
    public function updateApplyStatus($id = '', $status = '')
    {
        (new FriendStatus())->goCheck();

        //判断该申请是不是自己的，操作该申请是不是合法。
        $apply = FriendsService::checkOperateApply($id);

        if ($apply->status == FriendsApplyStatusEnum::Deny) {
            throw new FriendsException([
                'msg' => '好友申请不存在',
                'errorCode' => 80003,
            ]);
        } elseif ($apply->status == FriendsApplyStatusEnum::Pass) {
            throw new FriendsException([
                'msg' => 'TA已经是你好友',
                'errorCode' => 80002,
            ]);
        }
        FriendsService::changeApplyStatus($status, $apply);

        throw new SuccessMessage([
            'msg' => '操作成功',
        ]);
    }

    //删除好友申请
    public function deleteApply($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();
        $apply = FriendsService::checkOperateApply($id);
        WhFriendsApply::destroy($id);
        throw new SuccessMessage([
            'msg' => '操作成功',
        ]);
    }

    //获取好友申请列表
    public function getApplyList()
    {
        $uid = Token::getCurrentUid();
        $applyList = WhFriendsApply::getList($uid);
        if ($applyList->isEmpty()) {
            throw new FriendsException([
                'msg' => '暂时没有好友申请',
                'errorCode' => 80001,
            ]);
        }
        return $this->xdreturn($applyList);
    }


    //获取自己的好友列表
    public function getList()
    {
        $uid = Token::getCurrentUid();
        $friend_list = WhFriends::getFriendList($uid);
        if ($friend_list->isEmpty()) {
            throw new FriendsException();
        }
        return $this->xdreturn($friend_list);
    }
}