<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/9
 * Time: 13:18
 */

namespace app\lib\exception;


class MomentsException extends BaseException
{
    public $msg = '请求的动态不存在';
    public $errorCode = 70000;
}