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
use app\xdapi\model\WhFriendsApply;
use app\xdapi\service\Token;
use app\xdapi\validate\FriendStatus;
use app\xdapi\validate\IDMustBePositiveInt;
use think\Db;

class Friends extends BaseController
{
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

    public function updateApplyStatus($friend = '', $status = '')
    {
        (new FriendStatus())->goCheck();
        $uid = Token::getCurrentUid();
        $apply = WhFriendsApply::checkApplyExist($friend, $uid);
        if (!$apply || $apply->status == FriendsApplyStatusEnum::Deny) {
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
        if (FriendsApplyStatusEnum::Pass == $status) {
            Db::startTrans();
            try {
                WhFriendsApply::update(['id' => $apply->id, 'status' => $status]);
                $dataArray = [
                    ['my_id' => $friend, 'friend_id' => $uid],
                    ['my_id' => $uid, 'friend_id' => $friend],
                ];
                $whFriendApply = new WhFriendsApply();
                $whFriendApply->saveAll($dataArray);
                Db::commit();
            } catch(Exception $ex) {
                Db::rollback();
                throw $ex;
            }
        } else {
            WhFriendsApply::update(['id' => $apply->id, 'status' => $status]);
        }
        throw new SuccessMessage([
            'msg' => '操作成功',
        ]);
    }

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



    public function getList()
    {
        $uid = Token::getCurrentUid();
    }
}