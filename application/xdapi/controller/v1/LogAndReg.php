<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/6
 * Time: 10:00
 */

namespace app\xdapi\controller\v1;


use app\xdapi\controller\BaseController;
use app\xdapi\validate\RegisterNew;

class LogAndReg extends BaseController
{
    public function register($mobile = '', $pwd = '', $code = '')
    {
        //注册流程
        //1. 判断手机号码是否已被注册
        //2.判断验证码是否正确
        //3.账号密码加密新增数据库
        //4.注册成功，即生成token返回给客户端，保存登陆状态
        (new RegisterNew())->goCheck();
        //检查手机号码是否被注册
        $user = UserModel::checkMobile($mobile);
        if ($user) {
            throw new LogAndRegException();
        }
        //检查验证码是否正确
        $codeInfo = SmsCodeModel::checkCode($mobile, $code, SmsCodeTypeEnum::ToRegister);
        if (!$codeInfo || $codeInfo['code'] != $code || $codeInfo['expire_time'] < time() || 1 == $codeInfo['is_use']) {
            throw new LogAndRegException([
                'msg' => '验证码不匹配或已过期',
                'errorCode' => 20002,
            ]);
        } else {
            $timenow = time();
            //修改验证码使用状态
            SmsCodeModel::changeStatus($mobile, $code, SmsCodeTypeEnum::ToRegister, $timenow);
            //新增用户数据库
            $dataArray = [
                'mobile' => $mobile, 'password' => md5($pwd), 'last_login' => $timenow,
                'username' => "fpw_".self::getRandChar(6), 'uploadimgurl' => '/images/moren.jpg',
            ];
            $user = UserModel::create($dataArray);
            if ($user->id) {
                $reg = new LoginToken();
                $token = $reg->get($mobile, $pwd, 'register', $user->id);
                return json([
                    'error_code' => 'ok',
                    'token' => $token,
                ]);
            }
        }
    }

    public function login($mobile = '', $pwd = '')
    {

    }
}