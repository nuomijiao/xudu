<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/29
 * Time: 19:39
 */

namespace app\xdapi\validate;


class FeedbackNew extends BaseValidate
{
    protected $rule = [
        'content' => 'require'
    ];
    protected $message = [
        'content' => '反馈内容不能为空',
    ];
}