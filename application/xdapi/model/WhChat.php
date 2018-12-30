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

    public static function checkUsersRole($myId, $toId)
    {
        return self::where(['from_id' => $myId, 'to_id' => $toId])->whereOr(['to_id' => $myId, 'from_id' => $toId])->order('id', 'desc')->limit(1)->find();
    }

    public static function getTalkInDays($time, $myId, $toId)
    {
        return self::where("`create_time` >= ".$time." AND ((`from_id` = "."$myId"." AND `to_id` =".$toId.") OR (`to_id` = ".$myId." AND `from_id` = ".$toId."))")->where(['from_id' => $myId, 'to_id' => $toId])->whereOr(['to_id' => $myId, 'from_id' => $toId])->order('id', 'desc')->fetchSql(true)->select();
    }
}