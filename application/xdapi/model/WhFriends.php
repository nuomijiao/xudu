<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/11
 * Time: 15:37
 */

namespace app\xdapi\model;


class WhFriends extends BaseModel
{
    public function friends()
    {
        return $this->belongsTo('WhUser', 'friend_id', 'id');
    }

    public static function getFriendList($uid)
    {
        return self::with([
            'friends' => function($query) {
                $query->field(['id', 'head_img', 'user_name']);
            }
        ])->where('my_id', '=', $uid)->select();
    }

    //获取好友id列表
    public static function getFriendListId($uid)
    {
        return self::where('my_id', '=', $uid)->field('friend_id')->select();
    }

    public static function checkIsFriends($myId, $friendId)
    {
        return self::where(['my_id' => $myId, 'friend_id' => $friendId])->find();
    }
}