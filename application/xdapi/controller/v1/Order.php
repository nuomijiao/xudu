<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/18
 * Time: 9:32
 */

namespace app\xdapi\controller\v1;


use app\lib\exception\ActivityException;
use app\lib\exception\OrderException;
use app\xdapi\controller\BaseController;
use app\xdapi\model\WhActivity;
use app\xdapi\model\WhMemberGrade;
use app\xdapi\service\Token;
use app\xdapi\validate\IDMustBePositiveInt;
use app\xdapi\validate\OrderActNew;
use app\xdapi\service\Order as OrderService;
use app\xdapi\validate\OrderMemNew;

class Order extends BaseController
{
    public function sureActOrder($id = '')
{
    (new IDMustBePositiveInt())->goCheck();
    $uid = Token::getCurrentUid();
    $actInfo = WhActivity::getBrief($id);
    if (!$actInfo) {
        throw new ActivityException();
    }
    return $this->xdreturn($actInfo);
}

    public function makeActOrder()
    {
        $validate = new OrderActNew();
        $uid = Token::getCurrentUid();
        $request = $validate->goCheck();
        $dataArray = $validate->getDataByRule($request->post());
        $order = OrderService::createActOrder($dataArray, $uid);
        return $this->xdreturn($order);
    }

    public function sureMemOrder($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();
        $uid = Token::getCurrentUid();
        $memberInfo = WhMemberGrade::get($id);
        if (!$memberInfo) {
            throw new OrderException([
                'msg' => '会员项不存在',
                'errorCode' => 60001,
            ]);
        }
        return $this->xdreturn($memberInfo);
    }

    public function makeMemOrder()
    {
        $validate = new OrderMemNew();
        $uid = Token::getCurrentUid();
        $request = $validate->goCheck();
        $dataArray = $validate->getDataByRule($request->post());
        $order = OrderService::createMemOrder($dataArray, $uid);
        return $this->xdreturn($order);
    }
}