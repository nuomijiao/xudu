<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/28
 * Time: 20:28
 */

namespace app\xdapi\controller\v1;


use app\lib\exception\UserException;
use app\xdapi\controller\BaseController;
use app\xdapi\model\WhUser;
use app\xdapi\service\Token;

class User extends BaseController
{
    public function getUserInfo()
    {
        $uid = Token::getCurrentUid();
        $userInfo = WhUser::get($uid);
        if (!$userInfo) {
            throw new UserException();
        }
        if ($userInfo->member_end_time == 0 || $userInfo->member_end_time <= time()) {
            $userInfo->is_member = 0;
        } elseif ($userInfo->member_end_time > time()) {
            $userInfo->is_membet = 1;
        }
        return $this->xdreturn($userInfo);
    }
}