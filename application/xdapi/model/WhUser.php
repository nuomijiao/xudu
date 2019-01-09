<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/5
 * Time: 20:40
 */

namespace app\xdapi\model;


class WhUser extends BaseModel
{
    protected $autoWriteTimestamp = true;

    public function getHeadImgAttr($value) {
        return config('setting.domain').$value;
    }

    public static function checkUserByMobile($mobile)
    {
        $user = self::where('mobile_number', '=', $mobile)->field(['id','mobile_number','user_name', 'head_img'])->find();
        return $user;
    }

    public static function checkUser($mobile, $pwd)
    {
        $user = self::where(['mobile_number'=>$mobile, 'user_pwd'=>md5($pwd)])->find();
        return $user;
    }

    public static function checkUserByUserName($userName)
    {
        $user = self::where('user_name', '=', $userName)->find();
        return $user;
    }

    public static function checkUserByIdNumber($IdNumber)
    {
        $user = self::where('id_number', '=', $IdNumber)->find();
        return $user;
    }

    public static function getUserByKey($key)
    {
        return self::where('user_name', 'like', '%'.$key.'%')->field('id')->select();
    }

}