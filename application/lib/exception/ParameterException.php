<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/3
 * Time: 11:09
 */

namespace app\lib\exception;


class ParameterException extends BaseException
{
    public $msg = '参数错误';
    public $errorCode = 10000;
}