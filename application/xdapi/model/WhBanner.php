<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/3
 * Time: 15:40
 */

namespace app\xdapi\model;


class WhBanner extends BaseModel
{
    protected $hidden = [

    ];

    public function getImgUrlAttr($value)
    {
        return config('setting.domain').$value;
    }

    public static function getBanner() {
        $banner = self::order('sort_order asc')->select();
        return $banner;
    }
}