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
        'ids' => 'require|isIds',

    ];

    protected $message = [
        'title' => '动态标题不能为空',
        'ids' => 'ids格式有误',

    ];

}