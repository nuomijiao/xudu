<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/6
 * Time: 10:00
 */

namespace app\xdapi\controller\v1;


use app\lib\enum\SmsCodeTypeEnum;
use app\lib\exception\UserException;
use app\xdapi\controller\BaseController;
use app\xdapi\model\WhSmscode;
use app\xdapi\model\WhUser;
use app\xdapi\service\UserToken;
use app\xdapi\validate\LoginTokenGet;
use app\xdapi\validate\RegisterNew;
use app\xdapi\validate\ResetPwd;

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
        $user = WhUser::checkUserByMobile($mobile);
        if ($user) {
            throw new UserException([
                'msg' => '手机号码已注册，请直接登录',
                'errorCode' => 50002,
            ]);
        }
        //检查验证码是否正确
        $codeInfo = WhSmscode::checkCode($mobile, $code, SmsCodeTypeEnum::ToRegister);
        if (!$codeInfo || $codeInfo['validate_code'] != $code || $codeInfo['expire_time'] < time() || $codeInfo['using_time'] > 0) {
            throw new UserException([
                'msg' => '验证码不匹配或已过期',
                'errorCode' => 50005,
            ]);
        } else {
            $timenow = time();
            //修改验证码使用状态
            WhSmscode::changeStatus($mobile, $code, SmsCodeTypeEnum::ToRegister, $timenow);
            //新增用户数据库
            $dataArray = [
                'mobile_number' => $mobile, 'user_pwd' => md5($pwd), 'last_login' => $timenow,
                'user_name' => self::randUserName(), 'id_number' => self::randIdNumber(), 'head_img' => '/assets/img/user_head.png',
            ];
            $user = WhUser::create($dataArray);
            if ($user->id) {
                $reg = new UserToken();
                $token = $reg->getToken($user->id);
                return $this->xdreturn(['token'=>$token]);
            }
        }
    }

    public function login($mobile = '', $pwd = '')
    {
        (new LoginTokenGet())->goCheck();
        //检查手机是否注册
        $user = WhUser::checkUserByMobile($mobile);
        if (!$user) {
            throw new UserException([
                'msg' => '手机号还未注册',
                'errorCode' => 50003,
            ]);
        }
        //检查手机号密码是否正确
        $user = WhUser::checkUser($mobile, $pwd);
        if (!$user) {
            throw new UserException([
                'msg' => '手机号或密码不正确',
                'errorCode' => 50004
            ]);
        } else {
            $log = new UserToken();
            $token = $log->getToken($user->id);
            return $this->xdreturn(['token'=>$token]);
        }
    }

    public function resetPwd($mobile = '', $pwd = '', $pwd2 = '', $code = '')
    {
        (new ResetPwd())->goCheck();
        //检查两次密码是否一致
        if ($pwd !== $pwd2) {
            throw new UserException([
                'msg' => '两次密码不一致',
                'errorCode' => 50008,
            ]);
        }
        //检查手机是否注册
        $user = WhUser::checkUserByMobile($mobile);
        if (!$user) {
            throw new UserException([
                'msg' => '手机号还未注册',
                'errorCode' => 50003,
            ]);
        }

        //检查验证码是否正确
        $codeInfo = WhSmscode::checkCode($mobile, $code, SmsCodeTypeEnum::ToResetPwd);
        if (!$codeInfo || $codeInfo['validate_code'] != $code || $codeInfo['expire_time'] < time() || $codeInfo['using_time'] > 0) {
            throw new UserException([
                'msg' => '验证码不匹配或已过期',
                'errorCode' => 50005,
            ]);
        } else {
            $timenow = time();
            //修改验证码使用状态
            WhSmscode::changeStatus($mobile, $code, SmsCodeTypeEnum::ToResetPwd, $timenow);
            //修改用户密码
            WhUser::update(['id' => $user->id, 'user_pwd' => md5($pwd)]);

            $reg = new UserToken();
            $token = $reg->getToken($user->id);
            return $this->xdreturn(['token'=>$token]);

        }

    }

    private static function randUserName()
    {
        $userName = "xd_".self::getRandChar(6);
        $user = WhUser::checkUserByUserName($userName);
        if ($user) {
            self::randUserName();
        } else {
            return $userName;
        }
    }

    private static function randIdNumber()
    {
        $IdNumber = "xudu_".self::getRandChar(6);
        $user = WhUser::checkUserByIdNumber($IdNumber);
        if ($user) {
            self::randIdNumber();
        } else {
            return $IdNumber;
        }

    }

    private static function getRandChar($length)
    {
        $str = null;
        $strPol = "0123456789";
        $max = strlen($strPol) - 1;

        for ($i = 0;
             $i < $length;
             $i++) {
            $str .= $strPol[rand(0, $max)];
        }

        return $str;
    }
}