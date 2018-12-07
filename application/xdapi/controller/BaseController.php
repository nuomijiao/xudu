<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/3
 * Time: 13:16
 */

namespace app\xdapi\controller;


use think\Controller;


class BaseController extends Controller
{
    public function xdreturn($param)
    {
        return json([
            'error_code'=>'Success',
            'data'=> $param
        ]);
    }
}