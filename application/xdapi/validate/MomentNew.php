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
        'moment_img' => 'require|image'
    ];

    protected $message = [
        'title' => '动态标题不能为空',
        'moment_img' => '请上传图片文件'
    ];

}