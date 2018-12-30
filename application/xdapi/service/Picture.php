<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/10
 * Time: 18:44
 */

namespace app\xdapi\service;


use app\lib\exception\ParameterException;

class Picture
{

    public static function uploadImg($img, $directory)
    {
        $info = $img->validate(['ext'=>'jpg,png,gif,JPG,PNG,GIF', 'type' => 'image/jpeg,image/png,image/gif'])->rule('md5')->move(ROOT_PATH.'public'.DS.$directory);
        if ($info) {
            $dataArray = [
                'url' => DS.$directory.DS.$info->getSaveName(),
                'from' => 1,
                'head_img' => DS.$directory.DS.$info->getSaveName(),
            ];
            return $dataArray;
        } else {
            throw new ParameterException([
                'msg' => '上传图片参数有错误',
            ]);
        }
    }
}