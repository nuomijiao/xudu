<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/28
 * Time: 12:48
 */

namespace app\xdapi\validate;


class ActByCat extends BaseValidate
{
    protected $rule = [
        'id' => 'isPositiveInteger',
        'page' => 'isPositiveInteger',
        'size' => 'isPositiveInteger',
        'type' => 'require|in:0,1',
    ];

    protected $message = [
        'id' => 'id必须是正整数',
        'page' => '分页参数必须是正整数',
        'size' => '分页参数必须是正整数',
    ];
}