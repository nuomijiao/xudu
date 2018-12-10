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


    public function allImg()
    {
        return $this->hasMany('WhMomentImage', 'moment_id', 'id');
    }

    public static function getHotMoments($page, $size)
    {
        return self::with(['allImg'])->paginate($size, true, ['page' => $page]);
    }
}