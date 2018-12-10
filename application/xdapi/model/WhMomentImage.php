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
    public function getUrlAttr($value, $dat)
    {
        return $this->prefixImgUrl($value, $data);
    }
}