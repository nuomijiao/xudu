<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/11
 * Time: 15:35
 */

namespace app\xdapi\validate;


class IDMustBePositiveInt extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPostiveInteger',
    ];

    protected $message = [
        'id' => 'id必须是正整数',
    ];
}