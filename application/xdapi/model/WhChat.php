<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/30
 * Time: 10:06
 */

namespace app\xdapi\model;


class WhChat extends BaseModel
{
    protected $autoWriteTimestamp= true;

    public function my()
    {
        return $this->belongsTo('WhUser', 'from_id', 'id');
    }

    public function to()
    {
        return $this->belongsTo('WhUser', 'to_id', 'id');
    }

    public static function getLastNew($myId, $toId)
    {
        return self::where("(`from_id` = "."$myId"." AND `to_id` =".$toId.") OR (`to_id` = ".$myId." AND `from_id` = ".$toId.")")->order('id', 'desc')->limit(1)->find();
    }

    public static function getTalkInDays($myId, $toId, $page, $size)
    {
        return self::with([
            'my' => function ($query) {
                $query->field(['id', 'head_img']);
            }
        ])->where("(`from_id` = "."$myId"." AND `to_id` =".$toId.") OR (`to_id` = ".$myId." AND `from_id` = ".$toId.")")->order('id', 'desc')->paginate($size, true, ['page' => $page]);
    }
}