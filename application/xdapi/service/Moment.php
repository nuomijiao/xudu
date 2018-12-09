<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/9
 * Time: 15:53
 */

namespace app\xdapi\service;


use app\lib\exception\ParameterException;
use think\Validate;

class Moment
{
    public static function checkImg($img)
    {
        $type = $img->getMime();
        if (in_array($type, ['image/jpeg','image/png','image/gif'])) {
            return true;
        } else {
            return false;
        }
    }
}