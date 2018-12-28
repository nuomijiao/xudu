<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/18
 * Time: 10:25
 */

namespace app\xdapi\validate;


class OrderActNew extends BaseValidate
{
    protected $rule = [
        'act_id' => 'require|isPositiveInteger',
        'address' => 'require',
        'fullname' => 'require',
        'mobile' => 'require|isMobile',
        'adult_number' => 'require|isPositiveInteger',
        'child_number' => 'require|isInteger',
        'pay_way' => 'require|in:1,2',
    ];

    protected $message = [
        'act_id' => '活动id必须为正整数',
        'address' => '当前位置不能为空',
        'fullname' => '姓名不能为空',
        'mobile' => '手机号格式不正确',
        'adult_number' => '成人数量必须是正整数',
        'child_number' => '儿童数量必须是正整数',
    ];
}