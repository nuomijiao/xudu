<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/10
 * Time: 17:39
 */

namespace app\xdapi\model;


class WhMomentImage extends BaseModel
{
    protected $hidden = [
        'id', 'moment_id', 'from', 'delete_time'
    ];

    public function getUrlAttr($value, $data)
    {
        return $this->prefixImgUrl($value, $data);
    }
}