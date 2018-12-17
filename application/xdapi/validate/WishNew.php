<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/17
 * Time: 13:20
 */

namespace app\xdapi\validate;


class WishNew extends BaseValidate
{
    protected $rule = [
        'wish' => 'require',
    ];

    protected $message = [
        'wish' => "愿望不能为空",
    ];
}