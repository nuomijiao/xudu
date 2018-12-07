<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/5
 * Time: 17:05
 */

namespace app\xdapi\validate;




class SmsCode extends BaseValidate
{
    protected $rule = [
        'mobile' => 'require|isMobile',
    ];

    protected $message = [
        'mobile' => '请输入正确手机号',
    ];
}