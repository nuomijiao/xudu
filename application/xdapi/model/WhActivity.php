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

    public function category()
    {
        return $this->belongsTo('WhActivity', 'cat_id', 'id');
    }

    public static function getActivity() {
        $activity = self::order('id desc')->select();
        return $activity;
    }
}