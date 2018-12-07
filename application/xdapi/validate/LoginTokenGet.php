<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/7
 * Time: 16:00
 */

namespace app\xdapi\validate;


class LoginTokenGet extends BaseValidate
{
    protected $rule = [
        'mobile' => 'require|isMobile',
        'pwd' => 'require|isNotEmpty'
    ];

    protected $message = [
        'mobile' => '请填写正确的手机号',
        'pwd' => '密码不能为空',
    ];
}