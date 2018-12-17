<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/17
 * Time: 13:17
 */

namespace app\xdapi\controller\v1;


use app\lib\exception\UserException;
use app\xdapi\controller\BaseController;
use app\xdapi\model\WhWishes;
use app\xdapi\service\Token;
use app\xdapi\validate\PagingParameter;
use app\xdapi\validate\WishNew;

class Wishes extends BaseController
{
    //添加愿望
    public function addWish()
    {
        $request = (new WishNew())->goCheck();
        $uid = Token::getCurrentUid();
        //检查一年内是否许过愿望。
        $wish = WhWishes::checkWishInYear($uid);
        if ($wish) {
            throw new UserException([
                'msg' => '一年之内只能许一次愿望',
                'errorCode' => 50006,
            ]);
        }
        $newWish = WhWishes::create([
            'user_id' => $uid,
            'wish' => $request->param('wish'),
        ]);
        return $this->xdreturn($newWish);
    }

    //愿望列表
    public function getWishes($page = 1, $size = 13)
    {
        (new PagingParameter())->goCheck();
        $uid = Token::getCurrentUid();
        $pagingWishes = WhWishes::getWishes($page, $size);

        if ($pagingWishes->isEmpty()) {
            throw new UserException([
                'msg' => '愿望已见底',
                'errorCode' => 50008,
            ]);
        }
        $data = $pagingWishes->toArray();
        return json([
            'error_code' => 'Success',
            'data' => $data,
            'current_page' => $pagingWishes->getCurrentPage(),
        ]);
    }

    //我的愿望
    public function myWish()
    {
        $uid = Token::getCurrentUid();
        $wish = WhWishes::checkWishInYear($uid);
        if (!$wish) {
            throw new UserException([
                'msg' => '您还没有许愿',
                'errorCode' => 50007
            ]);
        }
        return $this->xdreturn($wish);
    }
}