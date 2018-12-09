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
        $rule = ['type' => 'image/jpeg', 'image/png', 'image/gif', 'ext' => 'jpg,JPG,gif,GIF,png,PNG'];
        $validate = new Validate($rule);
        $data = array();
        $data['type'] = $img->getMime();
        $data['ext'] = $img->getExtension();
        print_r($data);die;

    }
}