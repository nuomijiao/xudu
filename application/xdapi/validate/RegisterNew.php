<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/6
 * Time: 18:12
 */

namespace app\xdapi\validate;


class RegisterNew extends BaseValidate
{
    protected $rule = [
        'mobile' => 'require|isMobile',
        'pwd' => 'require|isNotEmpty',
        'code' => 'require|isCode',
    ];

    protected $message = [
        'mobile' => '请输入正确的手机号',
        'pwd' => '密码不能为空',
        'code' => '验证码为6位数字',
    ];

    public function isCode($value)
    {
        $rule = '/^\d{'.config('aliyun.sms_KL').'}$/';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}