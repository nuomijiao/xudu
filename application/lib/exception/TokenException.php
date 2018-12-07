<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/6
 * Time: 10:34
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
    public $msg = 'Token已过期或无效Token';
    public $errorCode = 10001;
}