<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/11
 * Time: 18:19
 */

namespace app\lib\enum;


class FriendsApplyStatus
{
    //待处理
    const Wait = 0;

    //通过
    const Pass = 1;

    //拒绝
    const Deny = 2;
}