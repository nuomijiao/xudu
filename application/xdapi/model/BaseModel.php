<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/3
 * Time: 15:39
 */

namespace app\xdapi\model;


use think\Model;

class BaseModel extends Model
{
    protected function prefixImgUrl($value, $data)
    {
        $finalUrl = $value;
        if (1 == $data['from']) {
            $finalUrl = config('setting.domain')
        }
    }
}