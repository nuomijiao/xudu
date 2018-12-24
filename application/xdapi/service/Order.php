<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/18
 * Time: 10:43
 */

namespace app\xdapi\service;


use app\lib\enum\OrderStatusEnum;
use app\lib\enum\OrderTypeEnum;
use app\lib\exception\ActivityException;
use app\lib\exception\OrderException;
use app\xdapi\model\WhActivity;
use app\xdapi\model\WhActOrder;
use app\xdapi\model\WhMemberGrade;
use app\xdapi\model\WhMemOrder;

class Order
{
    public static function createActOrder($dataArray, $uid)
    {
        //检查活动是否存在
        $act = WhActivity::get($dataArray['act_id']);
        if (!$act) {
            throw new ActivityException();
        }
        //检查是否是会员
        $member = UserToken::checkIsMember($uid);
        $data = $dataArray;
        $data['user_id'] = $uid;
        $data['order_sn'] = self::makeOrderNo(OrderTypeEnum::Activity);
        $data['status'] = OrderStatusEnum::Unpaid;
        if ($member) {
            $data['adult_price'] = $act->act_ad_member_price;
            $data['child_price'] = $act->act_ch_member_price;
        } else {
            $data['adult_price'] = $act->act_ad_price;
            $data['child_price'] = $act->act_ch_price;
        }
        $data['snap_name'] = $act->act_name;
        $data['snap_image'] = $act->getData('main_img');
        $data['act_snap'] = json_encode($act);
        $data['total_price'] = $data['adult_price'] * $data['adult_number'] + $data['child_price'] * $data['child_number'];
        $order = WhActOrder::create($data);
        return $order;
    }


    public static function CreateMemOrder($dataArray, $uid)
    {
        $mem = WhMemberGrade::get($dataArray['mem_id']);
        if (!$mem) {
            throw new OrderException([
                'msg' => '会员项不存在',
                'errorCode' => 600021,
            ]);
        }
        $data = $dataArray;
        $data['user_id'] = $uid;
        $data['order_sn'] = self::makeOrderNo(OrderTypeEnum::Member);
        $data['status'] = OrderStatusEnum::Unpaid;
        $data['price'] = $mem->price;
        $data['name_snap'] = $mem->name;
        $data['brief_snap'] = $mem->brief;
        $data['snap'] = json_encode($mem);
        $order = WhMemOrder::create($data);
        return $order;

    }

    private static function makeOrderNo($type)
    {
        if (OrderTypeEnum::Activity == $type) {
            $yCode = array('A', 'B', 'C', 'E', 'F');
        } elseif (OrderTypeEnum::Member == $type) {
            $yCode = array('H', 'K', 'M', 'N', 'R');
        }
        $orderSn = $yCode[intval(date('Y')) - 2018] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
    }


}