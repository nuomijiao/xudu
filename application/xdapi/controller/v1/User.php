<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/28
 * Time: 20:28
 */

namespace app\xdapi\controller\v1;


use app\xdapi\controller\BaseController;
use app\xdapi\model\WhUser;
use app\xdapi\service\Token;

class User extends BaseController
{
    public function getUserInfo()
    {
        $uid = Token::getCurrentUid();
        $userInfo = WhUser::get($uid);
        return $this->xdreturn($userInfo);
    }
}