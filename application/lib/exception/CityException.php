<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/28
 * Time: 13:33
 */

namespace app\lib\exception;


class CityException extends BaseException
{
    public $msg = '请求的城市不存在';
    public $errorCode = 90000;
}