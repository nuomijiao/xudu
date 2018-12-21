<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/3
 * Time: 10:27
 */

namespace app\xdapi\controller\v1;

use app\lib\exception\CategoryException;
use app\xdapi\controller\BaseController;
use app\xdapi\model\WhCategory;


class Category extends BaseController
{

    public function getCategory(){

        $category = WhCategory::getCategory();
        if ($category->isEmpty()) {
            throw new CategoryException();
        }
        return $this->xdreturn($category);
    }


    //后台用接口
    public function catlist()
    {
        $cat_list = WhCategory::field(['cat_name'])->select()->toArray();
        $cat_name = [];
        foreach ($cat_list as $key=>$value){
            array_push($cat_name, $value['cat_name']);
        }
        return json($cat_name);
    }
}