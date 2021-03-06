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
        return self::where('level', '=', 2)->whereNotIn('id', '1707,1822,2306,3317')->select();
    }

    public static function getCityId($name)
    {
        return self::where('name', 'like', "%".$name."%")->where('level', '=', 2)->find();
    }
}