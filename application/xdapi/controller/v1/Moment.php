<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/9
 * Time: 13:37
 */

namespace app\xdapi\controller\v1;


use app\lib\exception\ParameterException;
use app\xdapi\controller\BaseController;
use app\xdapi\service\Token;
use app\xdapi\validate\MomentNew;
use app\xdapi\service\Moment as MomentService;

class Moment extends BaseController
{
    public function addMoment()
    {
        $request = (new MomentNew())->goCheck();
        $moment_img = $request->file('moment_img');
        $title = $request->param('title');
        $location = $request->param('location');
        if (!empty($moment_img)) {
            if (is_object($moment_img)) {
                throw new ParameterException([
                   'msg' => '上传图片参数错误',
                ]);
            }
            foreach ($moment_img as $key => $value) {
                if(!MomentService::checkImg($value)) {
                    throw new ParameterException([
                        'msg' => '上传图片参数错误',
                    ]);
                }
            }
        }
        $uid = Token::getCurrentUid();
        //上传动态
        $data = MomentService::releaseMoment($uid,$moment_img,$title,$location);
        return $this->xdreturn($data);
    }
}