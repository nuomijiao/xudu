<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/30
 * Time: 21:10
 */

namespace app\xdapi\validate;


class NotifyOrder extends BaseValidate
{
    protected $rule = [
        'ordersn' => 'require|alphaNum',
    ];

    protected $message = [
        'ordersn' => '订单号只能为数字和字母'
    ];
}