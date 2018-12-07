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


    /**
     * 成功返回
     * @param $result
     * @param string $msg
     * @param $code
     * @author wuyading
     */
    public function success_return($result = null, $msg = '', $code = 'ok')
    {
        $return['code'] = $code;
        if($msg) {
            $return['msg'] = $msg;
        } else {
            $return['msg'] = '操作成功';
        }
        $return['response'] = $result;
        exit(json_encode($return));
    }

    /**
     * 失败返回
     * @param string $msg
     * @param string $code
     * @author wuyading
     */
    public function error($msg = '', $code = 'error')
    {
        $result['code'] = $code;
        if($msg) {
            $result['msg'] = $msg;
        } else {
            $result['msg'] = '操作失败';
        }
        exit(json_encode($result));
    }



}