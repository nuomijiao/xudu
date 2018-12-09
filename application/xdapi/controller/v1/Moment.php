<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/9
 * Time: 13:37
 */

namespace app\xdapi\controller\v1;


use app\xdapi\controller\BaseController;
use app\xdapi\service\Token;
use app\xdapi\validate\MomentNew;

class Moment extends BaseController
{
    public function addMoment()
    {
        (new MomentNew())->goCheck();
        $uid = Token::getCurrentUid();

    }
}