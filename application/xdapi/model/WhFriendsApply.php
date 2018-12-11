<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/11
 * Time: 15:37
 */

namespace app\xdapi\model;


class WhFriendsApply extends BaseModel
{
    protected $autoWriteTimestamp = true;

    protected $hidden = [
        'create_time', 'update_time',
    ];

    public function friends()
    {
        return $this->belongsTo('WhUser', 'my_id', 'id');
    }

    public static function checkApplyExist($uid, $id)
    {
        return self::where(['my_id' => $uid, 'friend_id' => $id])->order('create_time', 'desc')->limit(1)->find();
    }

    public static function getList($uid)
    {
        $list = self::with([
            'friends' => function ($query) {
                $query->field(['id', 'head_img', 'user_name']);
            }
        ])->where('friend_id', '=', $uid)->order('create_time', 'asc')->select();
        return $list;
    }

}