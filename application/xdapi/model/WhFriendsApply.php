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

    public function friends()
    {
        return $this->belongsTo('WhUser', 'my_id', 'id');
    }

    public static function getList($uid)
    {
        $list = self::with([
            'friends' => function ($query) {
                $query->field('id', 'head_img', 'user_name');
            }
        ])->where('friend_id', '=', $uid)->order('create_time', 'asc')->select();
        return $list;
    }
}