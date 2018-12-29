<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/10
 * Time: 18:44
 */

namespace app\xdapi\service;


class Picture
{
    public static function checkImg($img)
    {
        $type = $img->getMime();
        if (in_array($type, ['image/jpeg','image/png','image/gif'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function uploadImg($img, $directory)
    {
        $info = $img->rule('md5')->move(ROOT_PATH.'public'.DS.$directory);
        if ($info) {
            $dataArray = [
                'url' => DS.$directory.DS.$info->getSaveName(),
                'from' => 1,
                'head_img' => DS.$directory.DS.$info->getSaveName(),
            ];
            return $dataArray;
        } else {
            throw new Exception($info->getError());
        }
    }
}