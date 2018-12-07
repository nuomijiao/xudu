<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/3
 * Time: 16:50
 */

namespace app\lib\exception;


class CategoryException extends BaseException
{

    public $msg = '请求分类类目不存在';
    public $errorCode = 40000;
}