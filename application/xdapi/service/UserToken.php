<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/6
 * Time: 17:47
 */

namespace app\xdapi\service;


use app\lib\exception\UserException;
use app\lib\exception\TokenException;
use app\xdapi\model\WhUser;

class UserToken extends Token
{
    public function getToken($mobile, $pwd, $type = 'login', $id = null)
    {
        if ('login' == $type) {
            //如果是登陆
            $mobile_exist = WhUser::checkUserByMobile($mobile);
            if (!$mobile_exist) {
                throw new UserException([
                    'msg' => '手机号还未注册',
                    'errorCode' => 50003,
                ]);
            } else {
                $user = WhUser::checkUser($mobile, $pwd);
                if (!$user) {
                    throw new UserException([
                        'msg' => '手机号或密码不正确',
                        'errorCode' => 50004
                    ]);
                } else {
                    $uid = $user->id;
                }
            }
        } else if ('register' == $type) {
            //如果是注册
            $uid = $id;
        }
        $values = [
            'uid' => $uid,
        ];
        $token = $this->saveToCache($values);
        return $token;
    }

    private function saveToCache($values){
        $token = self::generateToken();
        $expire_in = config('secure.token_expire_in');
        $result = cache($token, json_encode($values), $expire_in);
        if(!$result){
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10002
            ]);
        }
        return $token;
    }
}