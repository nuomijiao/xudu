<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/18
 * Time: 13:52
 */

namespace app\lib\enum;


class OrderStatusEnum
{
    //未支付
    const Unpaid = 1;

    //已支付
    const Paid = 2;

    //申请退款，
    const ApplyRefund = 3;

    //退款成功
    const Refunded = 4;
}