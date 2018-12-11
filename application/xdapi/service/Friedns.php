<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/11
 * Time: 20:40
 */

namespace app\xdapi\service;


use app\lib\enum\FriendsApplyStatusEnum;
use app\lib\exception\FriendsException;
use app\lib\exception\ParameterException;
use app\xdapi\model\WhFriends;
use app\xdapi\model\WhFriendsApply;
use think\Db;
use think\Exception;

class Friedns
{
    public static function checkOperateApply($applyId)
    {
        $apply = WhFriendsApply::get($applyId);
        if (!$apply) {
            throw new FriendsException([
                'msg' => '好友申请不存在',
                'errorCode' => 80003,
            ]);
        }
        $user_id = Token::isValidOperate($apply->friend_id);
        if (!$user_id) {
            throw new ParameterException([
                'msg' => '不能操作他人的申请',
                'errorCode' => 10003,
            ]);
        }
        return $apply;
    }

    public static function changeApplyStatus($status, $apply)
    {
        if (FriendsApplyStatusEnum::Pass == $status) {
            Db::startTrans();
            try {
                WhFriendsApply::update(['id' => $apply->id, 'status' => $status]);
                $dataArray = [
                    ['my_id' => $apply->my_id, 'friend_id' => $apply->friend_id],
                    ['my_id' => $apply->friend_id, 'friend_id' => $apply->my_id],
                ];
                $whFriends = new WhFriends();
                $whFriends->saveAll($dataArray);
                Db::commit();
            } catch(Exception $ex) {
                Db::rollback();
                throw $ex;
            }
        } else {
            WhFriendsApply::update(['id' => $apply->id, 'status' => $status]);
        }
    }
}