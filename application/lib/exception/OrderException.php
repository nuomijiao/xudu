<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/7
 * Time: 13:28
 */

namespace app\lib\exception;


class OrderException extends BaseException
{
    public $msg = '请求的订单不存在';
    public $errorCode = 60000;
}