<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/18
 * Time: 9:32
 */

namespace app\xdapi\controller\v1;


use app\lib\exception\ActivityException;
use app\xdapi\controller\BaseController;
use app\xdapi\model\WhActivity;
use app\xdapi\service\Token;
use app\xdapi\validate\IDMustBePositiveInt;
use app\xdapi\validate\OrderActNew;
use app\xdapi\service\Order as OrderService;

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
}