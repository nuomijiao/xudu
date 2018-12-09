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
        $request = (new MomentNew())->goCheck();
        $moment_img = $request->file('moment_img');

        //验证上传文件是否是图片
        $rules = ['ext' => 'jpg,png,gif,JPG,PNG,GIF', 'type' => 'image/jpeg,image/png,image/gif'];
        foreach ($moment_img as $key => $value) {
            $value -> validate($rules);
        }

        $uid = Token::getCurrentUid();

    }
}