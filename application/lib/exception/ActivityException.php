<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/7
 * Time: 8:50
 */

namespace app\lib\exception;


class ActivityException extends BaseException
{
    public $msg = '请求活动类目不存在';
    public $errorCode = 20000;
}