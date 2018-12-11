<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/9
 * Time: 14:21
 */

namespace app\xdapi\model;


class WhMoments extends BaseModel
{
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;

    public function getCreateTimeAttr($value, $data) {
        return date("Y年m月d日", $value);
    }

    public function allImg()
    {
        return $this->hasMany('WhMomentImage', 'moment_id', 'id');
    }

    public function userInfo() {
        return $this->belongsTo('WhUser', 'user_id', 'id');
    }

    public static function getHotMoments($page, $size)
    {
        return self::with(['allImg','userInfo'])->order('zan_number', 'desc')->paginate($size, true, ['page' => $page]);
    }
}