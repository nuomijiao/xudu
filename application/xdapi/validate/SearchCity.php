<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/28
 * Time: 13:26
 */

namespace app\xdapi\validate;


class SearchCity extends BaseValidate
{
    protected $rule = [
        'name' => 'require'
    ];

    protected $message = [
        'name' =>'城市名称不能为空',
    ];
}