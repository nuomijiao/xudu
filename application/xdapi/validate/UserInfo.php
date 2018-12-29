<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/29
 * Time: 18:13
 */

namespace app\xdapi\validate;


class UserInfo extends BaseValidate
{
    protected $rule = [
        'user_name' => 'require|chsDash',
        'sex' => 'require|in:1,2,3',
        'province_id' => 'require|isPositiveInteger',
        'city_id' => 'require|isPositiveInteger',
        'sign' => 'chsDash',
    ];

    protected $message = [
        'user_name' => '名字只能是汉字、字母、数字和下划线_及破折号-',
        'province_id' => '省id必须为正整数',
        'city_id' => '市id必须为正整数',
        'sign' => '签名只能是汉字、字母、数字和下划线_及破折号-',
    ];
}