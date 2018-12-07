<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/5
 * Time: 20:35
 */

namespace app\lib\exception;


class UserException extends BaseException
{

    public $msg = '发送次数过多，请稍后再试';
    public $errorCode = 50001;
}