<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/9
 * Time: 13:41
 */

namespace app\xdapi\validate;


class MomentNew extends BaseValidate
{
    protected $rule = [
        'title' => 'require',
        'moment_img' => 'file|image|fileExt:jpg,png,gif|fileMime:image/jpeg,image/png,image/gif'
    ];

    protected $message = [
        'title' => '动态标题不能为空',
        'moment_img.image' => '请上传图像文件',
        'moment_img.fileExt' => '上传文件类型不合法',
        'moment_img.fileMime' => '上传文件的Mine类型不合法',
    ];

}