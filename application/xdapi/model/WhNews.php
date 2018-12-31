<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/31
 * Time: 8:54
 */

namespace app\xdapi\model;


class WhNews extends BaseModel
{
    public function fromUser()
    {
        return $this->belongsTo('WhUser', 'from_id', 'id');
    }

    public static function getNewsByUid($uid, $page, $size)
    {
        return self::with([
            'fromUser' => function ($query) {
                $query->field(['id', 'user_name', 'head_img']);
            }
        ])->where('to_id', '=', $uid)->order('last_time', 'desc')->paginate($size, true, ['page' => $page]);
    }
}