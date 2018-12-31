<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/31
 * Time: 9:35
 */

namespace app\lib\exception;


class NewsException extends BaseException
{
    public $msg = '消息列表不存在';
    public $errorCode = 110000;
}