<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/31
 * Time: 9:23
 */

namespace app\xdapi\model;


class WhSystemNews extends BaseModel
{
    protected $autoWriteTimestamp = true;

    public function getMainImgAttr($value)
    {
        return config('setting.domain').$value;
    }

    public static function getSystemNews($page, $size)
    {
        return self::where('is_show', '=', 1)->field(['id', 'title', 'main_img', 'brief', 'create_time'])->order('create_time', 'desc')->paginate($size, true, ['page' => $page]);
    }

}