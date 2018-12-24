<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/24
 * Time: 11:19
 */

namespace app\xdapi\validate;


class OrderMemNew extends BaseValidate
{
    protected $rule = [
        'mem_id' => 'require|isPositiveInteger',
        'address' => 'require',
        'fullname' => 'require',
        'mobile' => 'require|isMobile',
        'pay_way' => 'require|in:1,2',
    ];

    protected $message = [
        'mem_id' => '会员项id必须为正整数',
        'address' => '当前位置不能为空',
        'fullname' => '姓名不能为空',
        'mobile' => '手机号格式不正确',
    ];
}