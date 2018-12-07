<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/3
 * Time: 15:40
 */

namespace app\xdapi\model;


class WhActivity extends BaseModel
{
    protected $hidden = [

    ];

    public static function getActivity() {
        $activity = self::order('sort_order asc')->select();
        return $activity;
    }
}