<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/17
 * Time: 18:05
 */

namespace app\xdapi\model;


class WhActCollect extends BaseModel
{
    public function collect()
    {
        return $this->belongsTo('WhActivity', 'act_id', 'id');
    }

    public static function checkIsCollect($id, $uid)
    {
        return self::where(['act_id' => $id, 'user_id' => $uid])->find();
    }

    public function getCollectActivity($uid, $page, $size)
    {
        return self::with([
            'collect' => function($query) {
                $query->field(['id', 'act_name', 'act_ad_price', 'start_time', 'city_id','main_img', 'is_hot']);
            }
        ])->where('user_id', '=', $uid)->where('delete_time', '<=', 0)->paginate($size, true, ['page' => $page]);
    }
}