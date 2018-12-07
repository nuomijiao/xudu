<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/3
 * Time: 15:40
 */

namespace app\xdapi\model;


class WhCategory extends BaseModel
{
    protected $hidden = [
        'id', 'sort_order'
    ];

    public static function getCategory() {
        $banner = self::order('sort_order asc')->select();
        return $banner;
    }
}