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

    public $msg = '用户不存在';
    public $errorCode = 50000;
}