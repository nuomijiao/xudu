<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/18
 * Time: 13:57
 */

namespace app\xdapi\model;


use app\lib\enum\OrderStatusEnum;

class WhActOrder extends BaseModel
{
    protected $autoWriteTimestamp = true;

    public static function getPayOrder($uid, $page, $size)
    {
        return self::where('user_id', '=', $uid)->where('status', '=', OrderStatusEnum::Paid)->paginate($size, true, ['page' => $page]);
    }
}