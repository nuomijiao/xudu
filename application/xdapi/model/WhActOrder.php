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

    public static function getTrip($uid)
    {
        return self::where(['user_id'=>$uid, 'status' => OrderStatusEnum::Paid])->where('snap_start_time', '>', time())->field(['id', 'user_id', 'snap_start_time'])->select();
    }
}