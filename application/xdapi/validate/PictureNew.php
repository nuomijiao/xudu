<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/29
 * Time: 15:27
 */

namespace app\xdapi\validate;


class PictureNew extends BaseValidate
{
    protected $rule = [
        'head_img' => 'require|image|fileExt:jpg,png,gif|fileMime:image/jpeg,image/png,image/gif',
    ];

    protected $message = [
        'head_img.require' => '图像文件不能为空',
        'head_img.image' => '请上传图像文件',
        'head_img.fileExt' => '上传文件类型不合法',
        'head_img.fileMime' => '上传文件的Mine类型不合法',
    ];
}