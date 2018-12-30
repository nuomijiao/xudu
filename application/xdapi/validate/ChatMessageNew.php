<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/30
 * Time: 9:46
 */

namespace app\xdapi\validate;


class ChatMessageNew extends BaseValidate
{
    protected $rule = [
        'content' =>'require|isNotEmpty',
        'to_id' => 'require|isPositiveInteger'
    ];

    protected $message = [
        'content' => '内容不能为空',
        'to_id' => '对方id不能为空',
    ];
}