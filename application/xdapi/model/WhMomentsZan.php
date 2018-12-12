<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/11
 * Time: 10:52
 */

namespace app\xdapi\model;


class WhMomentsZan extends BaseModel
{
    public static function checkZanExist($id, $uid)
    {
        return self::where(['moment_id'=>$id, 'user_id'=>$uid])->find();
    }
}