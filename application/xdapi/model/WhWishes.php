<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/17
 * Time: 13:27
 */

namespace app\xdapi\model;


class WhWishes extends BaseModel
{

    protected $autoWriteTimestamp = true;
    protected $updateTime = false;

    public function getCreateTimeAttr($value)
    {
        return date("Y年m月d日", $value);
    }

    public function user()
    {
        return $this->belongsTo('WhUser', 'user_id', 'id');
    }

    public static function checkWishInYear($uid)
    {
        return self::whereTime('create_time', 'year')->where('user_id', '=', $uid)->find();
    }

    public static function getWishes($page, $size)
    {
        return self::with([
            'user' => function($query) {
                $query->field(['id', 'user_name', 'head_img']);
            }
        ])->whereTime('create_time','year')->order('create_time', 'desc')->paginate($size, true, ['page' => $page]);
    }

}