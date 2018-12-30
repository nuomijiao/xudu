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
use app\xdapi\model\WhChat;
use app\xdapi\model\WhFriends;
use app\xdapi\model\WhFriendsApply;
use think\Db;
use think\Exception;

class Friends
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

    public function sendToFriends($myId, $toId, $content)
    {
//        //检查两人角色，是发送者From_id还是接收者to_id
//        $role = WhChat::checkUsersRole($myId, $toId);
//        $data = [
//            'content' => $content,
//        ];
//        if ($role) {
//            $data['from_id'] = $myId == $role->from_id ? $role->from_id : $role->to_id;
//            $data['to_id'] = $toId == $role->to_id ? $role->to_id : $role->from_id;
//            $data['talk_order'] = $role->talk_order + 1;
//        } else {
//            $data['from_id'] = $myId;
//            $data['to_id'] = $toId;
//            $data['talk_order'] = 1;
//        }
//        //发送消息
//        WhChat::create($data);
        //返回两天之内的消息
        $talkInfo = WhChat::getTalkInDays(time()- config('setting.day') * 24 * 3600, $myId, $toId);
        return $talkInfo;
    }

}