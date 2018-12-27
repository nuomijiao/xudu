<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/17
 * Time: 17:02
 */

namespace app\xdapi\model;


class WhRegion extends BaseModel
{
    public static function getCity()
    {
        return self::where('level', '=', 2)->select();
    }
}