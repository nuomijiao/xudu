<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/11
 * Time: 19:20
 */

namespace app\xdapi\validate;


class FriendStatus extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger',
        'status' => 'require|in:1,2',
    ];

    protected $message = [
        'id' => 'id必须是正整数',
    ];
}