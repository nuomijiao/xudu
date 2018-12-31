<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/31
 * Time: 9:24
 */

namespace app\xdapi\controller\v1;


use app\lib\exception\NewsException;
use app\xdapi\controller\BaseController;
use app\xdapi\model\WhSystemNews;
use app\xdapi\validate\IDMustBePositiveInt;
use app\xdapi\validate\PagingParameter;

class SystemNews extends BaseController
{
    public function getSystemNews($page = 1, $size = 10)
    {
        (new PagingParameter())->goCheck();
        $pagingNews = WhSystemNews::getSystemNews($page, $size);
        if ($pagingNews->isEmpty()) {
            throw new NewsException([
                'msg' => '系统消息列表已见底',
                'errorCode' => 11002,
            ]);
        }
        $data = $pagingNews->toArray();
        return json([
            'error_code' => 'Success',
            'data' => $data,
            'current_page' => $pagingNews->getCurrentPage(),
        ]);
    }

    public function getSystemNewsDetail($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $detail = WhSystemNews::get($id);
        if (!$detail) {
            throw new NewsException();
        }
        return $this->xdreturn($detail);
    }
}