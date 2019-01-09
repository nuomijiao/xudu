<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/18
 * Time: 9:32
 */

namespace app\xdapi\controller\v1;


use addons\epay\library\Service;
use app\lib\enum\OrderStatusEnum;
use app\lib\enum\OrderTypeEnum;
use app\lib\exception\ActivityException;
use app\lib\exception\OrderException;
use app\lib\exception\SuccessMessage;
use app\xdapi\controller\BaseController;
use app\xdapi\model\WhActivity;
use app\xdapi\model\WhActOrder;
use app\xdapi\model\WhMemberGrade;
use app\xdapi\model\WhMemOrder;
use app\xdapi\service\Order as OrderService;
use app\xdapi\service\Token;
use app\xdapi\validate\IDMustBePositiveInt;
use app\xdapi\validate\NotifyOrder;
use app\xdapi\validate\OrderActNew;
use app\xdapi\validate\OrderMemNew;
use think\Db;
use think\Exception;
use addons\epay\library\Service as PayService;

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


    //获取订单详情
    public function getActOrderDetail($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();
        //检查订单是不是自己的。
        $order = OrderService::checkOperate($id);
        $newOrder = json_decode($order->act_snap, true);
        $newOrder['order_id'] = $id;
        return $this->xdreturn($newOrder);
    }



    //$id为订单的id
    public function cancelActOrder($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();
        //检查订单是不是自己的。
        $order = OrderService::checkOperate($id);
        //判断如果活动开始时间已过，则不允许退款
        $start_time = json_decode($order->act_snap, true)['start_time'];
        if (time() > $start_time) {
            throw new OrderException([
                'msg' => '活动已经开始，不能退款',
                'errorCode' => 60002
            ]);
        }
        if ($order->status != OrderStatusEnum::Paid) {
            throw new OrderException([
                'msg' => '活动订单已取消或退款，不能再取消',
                'errorCode' => 60003
            ]);
        }
        //修改订单状态，申请退款
        $result = WhActOrder::update([
            'id' => $order->id,
            'status' => OrderStatusEnum::ApplyRefund,
        ]);
        throw new SuccessMessage([
            'msg' => '申请取消退款成功，等待后台审核'
        ]);
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

//    public function notifyActOrder($ordersn = '')
//    {
//        (new NotifyOrder())->goCheck();
//        $order = OrderService::checkOperate('', OrderTypeEnum::Activity, $ordersn);
//        WhActOrder::update([
//            'id' => $order->id,
//            'status' => OrderStatusEnum::Paid,
//        ]);
//        throw new SuccessMessage();
//    }
//
//    public function notifyMemOrder($ordersn = '')
//    {
//        (new NotifyOrder())->goCheck();
//        $order = OrderService::checkOperate('', OrderTypeEnum::Member, $ordersn);
//        //处理订单信息
//        Db::startTrans();
//        try {
//            //更新订单状态
//            WhMemOrder::update([
//                'id' => $order->id,
//                'status' => OrderStatusEnum::Paid,
//            ]);
//            //修改会员时间
//            OrderService::dealUserMemTime($order->user_id);
//            Db::commit();
//
//        } catch(Exception $ex) {
//            Db::rollback();
//            throw $ex;
//        }
//        throw new SuccessMessage();
//
//    }

    public function wxpayNotify()
    {
        $pay = PayService::checkNotify('wechat');
        if (!$pay) {
            echo '签名错误';
            return;
        }

//你可以直接通过$pay->verify();获取到相关信息
//支付宝可以获取到out_trade_no,total_amount等信息
//微信可以获取到out_trade_no,total_fee等信息
        $data = $pay->verify();
        $orderSn = $data['out_trade_no'];
        if ($data['attach'] == 1) {
            //活动订单
            $order = OrderService::checkOperate('', OrderTypeEnum::Activity, $orderSn);
            WhActOrder::update([
                'id' => $order->id,
                'status' => OrderStatusEnum::Paid,
            ]);
        } elseif ($data['attach'] == 2) {
            //会员订单
            $order = OrderService::checkOperate('', OrderTypeEnum::Member, $orderSn);
            //处理订单信息
            Db::startTrans();
            try {
                //更新订单状态
                WhMemOrder::update([
                    'id' => $order->id,
                    'status' => OrderStatusEnum::Paid,
                ]);
                //修改会员时间
                OrderService::dealUserMemTime($order->user_id);
                Db::commit();

            } catch(Exception $ex) {
                Db::rollback();
                throw $ex;
            }
        }


//下面这句必须要执行,且在此之前不能有任何输出
        echo $pay->success();

        return;
    }

    public function alipayNotify()
    {
        $pay = PayService::checkNotify('alipay');
        if (!$pay) {
            echo '签名错误';
            return;
        }

//你可以直接通过$pay->verify();获取到相关信息
//支付宝可以获取到out_trade_no,total_amount等信息
//微信可以获取到out_trade_no,total_fee等信息
        $data = $pay->verify();
        $orderSn = $data['out_trade_no'];
        $body = explode('-', $data['body']);
        if ($body[0] == '活动') {
            //活动订单
            $order = OrderService::checkOperate('', OrderTypeEnum::Activity, $orderSn);
            WhActOrder::update([
                'id' => $order->id,
                'status' => OrderStatusEnum::Paid,
            ]);
        } elseif ($body[0] == '会员') {
            //会员订单
            $order = OrderService::checkOperate('', OrderTypeEnum::Member, $orderSn);
            //处理订单信息
            Db::startTrans();
            try {
                //更新订单状态
                WhMemOrder::update([
                    'id' => $order->id,
                    'status' => OrderStatusEnum::Paid,
                ]);
                //修改会员时间
                OrderService::dealUserMemTime($order->user_id);
                Db::commit();

            } catch(Exception $ex) {
                Db::rollback();
                throw $ex;
            }
        }

//下面这句必须要执行,且在此之前不能有任何输出
        echo $pay->success();

        return;
    }

}