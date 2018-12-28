<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/5
 * Time: 17:03
 */

namespace app\xdapi\controller\v1;


use app\xdapi\service\SendSms;
use app\lib\enum\SmsCodeTypeEnum;
use app\lib\exception\UserException;
use app\lib\exception\SuccessMessage;
use app\xdapi\controller\BaseController;
use app\xdapi\model\WhSmscode;
use app\xdapi\model\WhUser;
use app\xdapi\validate\SmsCode;
use think\Exception;

class Sms extends BaseController
{
    public function registerSms() {
        $request = (new SmsCode())->goCheck();
        $mobile = $request->param('mobile');
        $user = WhUser::checkUserByMobile($mobile);
        if ($user) {
            throw new UserException([
                'msg' => '手机号码已注册，请直接登录',
                'errorCode' => 50002,
            ]);
        }
        $mobile_count = WhSmscode::checkByMobile($mobile, SmsCodeTypeEnum::ToRegister);
        if ($mobile_count > config('aliyun.sms_mobile_limit')) {
            throw new UserException([
                'msg' => '发送次数过多',
                'errorCode' => 50001,
            ]);
        } else {
            $code = $this->randomKeys(config('aliyun.sms_KL'));
            $sendSms = new SendSms($mobile, $code, config('aliyun.sms_TC1'));
            //返回stdClass
            $acsResponse = $sendSms->sendSms();
            if ('OK' == $acsResponse->Code) {
                $dataArray = [
                    'mobile_number' => $mobile, 'validate_code' => $code, 'type' => SmsCodeTypeEnum::ToRegister, 'create_time' => time(),
                    'expire_time' => '',
                ];
                WhSmscode::create($dataArray);
                throw new SuccessMessage([
                    'msg' => '验证码发送成功',
                ]);
            } else {
                throw new Exception($acsResponse->Message);
            }
        }
    }


    public function resetSms()
    {
        $request = (new SmsCode())->goCheck();
        $mobile = $request->param('mobile');
        $user = WhUser::checkUserByMobile($mobile);
        if (!$user) {
            throw new UserException([
                'msg' => '手机号还未注册',
                'errorCode' => 50003,
            ]);
        }
        $mobile_count = WhSmscode::checkByMobile($mobile, SmsCodeTypeEnum::ToResetPwd);
        if ($mobile_count > config('aliyun.sms_mobile_limit')) {
            throw new UserException([
                'msg' => '发送次数过多',
                'errorCode' => 50001,
            ]);
        } else {
            $code = $this->randomKeys(config('aliyun.sms_KL'));
            $sendSms = new SendSms($mobile, $code, config('aliyun.sms_TC2'));
            //返回stdClass
            $acsResponse = $sendSms->sendSms();
            if ('OK' == $acsResponse->Code) {
                $dataArray = [
                    'mobile_number' => $mobile, 'validate_code' => $code, 'type' => SmsCodeTypeEnum::ToResetPwd, 'create_time' => time(),
                    'expire_time' => '',
                ];
                WhSmscode::create($dataArray);
                throw new SuccessMessage([
                    'msg' => '验证码发送成功',
                ]);
            } else {
                throw new Exception($acsResponse->Message);
            }
        }
    }

    private function randomKeys($length)
    {
        $key='';
        $pattern='1234567890';
        for($i=0;$i<$length;++$i)
        {
            $key .= $pattern{mt_rand(0,9)}; // 生成php随机数
        }
        return $key;
    }

}