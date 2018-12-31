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
use app\lib\exception\NewsException;
use app\lib\exception\SuccessMessage;
use app\xdapi\controller\BaseController;
use app\xdapi\model\WhFriends;
use app\xdapi\model\WhFriendsApply;
use app\xdapi\model\WhNews;
use app\xdapi\service\Friends as FriendsService;
use app\xdapi\service\Token;
use app\xdapi\validate\ChatMessageNew;
use app\xdapi\validate\FriendStatus;
use app\xdapi\validate\IDMustBePositiveInt;
use app\xdapi\validate\PagingParameter;

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
    public function getList($keywords = '')
    {
        $uid = Token::getCurrentUid();
        $friend_list = WhFriends::getFriendList($uid, $keywords);
        if ($friend_list->isEmpty()) {
            throw new FriendsException();
        }
        return $this->xdreturn($friend_list);
    }

    //发送消息
    public function chat($content = '', $to_id = '')
    {
        (new ChatMessageNew())->goCheck();
        $uid = Token::getCurrentUid();
        //检查对方是不是好友
        $isFriends = WhFriends::checkIsFriends($uid, $to_id);
        if (!$isFriends) {
            throw new FriendsException([
                'msg' => '非好友不能发送消息',
                'errorCode' => 70009,
            ]);
        }
        //返回最近7天的聊天记录。
        $chatInfo = FriendsService::sendToFriends($uid, $to_id, $content);
        return $this->xdreturn($chatInfo);
    }

    //获取消息列表
    public function getNewsList($keywords = '', $page = 1, $size = 10)
    {
        (new PagingParameter())->goCheck();
        $uid = Token::getCurrentUid();
        $pagingNews = WhNews::getNewsByUid($uid, $page, $size, $keywords);
        if ($pagingNews->isEmpty()) {
            throw new NewsException([
                'msg' => '好友消息列表已见底',
                'errorCode' => 11001,
            ]);
        }
        $data = $pagingNews->toArray();
        return json([
            'error_code' => 'Success',
            'data' => $data,
            'current_page' => $pagingNews->getCurrentPage(),
        ]);
    }
}