<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/11
 * Time: 16:26
 */

namespace app\lib\exception;


class FriendsException extends BaseException
{
    public $msg = '好友列表为空';
    public $errorCode = 80000;
}