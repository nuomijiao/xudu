<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/31
 * Time: 9:54
 */

namespace app\xdapi\validate;


class CityId extends BaseValidate
{
    protected $rule = [
        'city_id' => 'isPositiveInteger',
    ];

    protected $message = [
        'city_id' => 'city_id必须是正整数',
    ];
}