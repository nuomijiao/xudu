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
use app\lib\exception\UserException;
use app\xdapi\model\WhChat;
use app\xdapi\model\WhFriends;
use app\xdapi\model\WhFriendsApply;
use app\xdapi\model\WhNews;
use app\xdapi\model\WhUser;
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

    public static function sendToFriends($myId, $toId, $content, $page, $size)
    {
        //获取两人最近的一条信息
        $lastNew = WhChat::getLastNew($myId, $toId);
        $data = [
            'content' => $content,
            'from_id' => $myId,
            'to_id' => $toId
        ];
        if ($lastNew) {
            $data['talk_order'] = $lastNew->talk_order + 1;
        } else {
            $data['talk_order'] = 1;
        }
        //发送消息
        WhChat::create($data);
        //产生消息列表
        //1判断是否有消息列表
        //更新或添加
        $newsList = WhNews::where(['from_id' => $myId, 'to_id' => $toId])->find();
        $data = [
            'last_time' => time(),
        ];
        if ($newsList) {
            WhNews::update([
                'id' => $newsList->id,
                'last_time' => $data['last_time'],
            ]);
        } else {
            $whNews = new WhNews();
            $list = [
                ['from_id'=>$myId,'to_id'=>$toId],
                ['to_id'=>$toId,'from_id'=>$myId]
            ];
            $whNews->saveAll($list);
        }

        $talkInfo = self::getChatInfo($myId, $toId, $page, $size);
        return $talkInfo;
    }

    public static function getChatInfo($myId, $toId, $page, $size)
    {
        $pagingtalkInfo = WhChat::getTalkInDays( $myId, $toId, $page, $size);
        if ($pagingtalkInfo->isEmpty()) {
            throw new UserException([
                'msg' => '消息已见底线',
                'errorCode' => 80004,
            ]);
        }

        $newTalkInfo = $pagingtalkInfo->toArray()['data'];

        foreach ($newTalkInfo as $key => &$value) {
            if ($value['from_id'] == $myId) {
                $value['mys'] = 1;
            } else {
                $value['mys'] = 0;
            }
        }
        return [
            'error_code' => 'Success',
            'data' => $newTalkInfo,
            'current_page' => $pagingtalkInfo->getCurrentPage(),
        ];
    }

    public static function getUserIds($keywords)
    {
        $users = WhUser::getUserByKey($keywords);
        $ids = '';
        if (!$users->isEmpty()) {
            foreach ($users->toArray() as $key => $value) {
                $ids .= $value['id'].",";
            }
            $ids = rtrim($ids, ',');
        }
        return $ids;
    }

}