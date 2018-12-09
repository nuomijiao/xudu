<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/9
 * Time: 13:41
 */

namespace app\xdapi\validate;


class MomentNew extends BaseValidate
{
    protected $rule = [
        'title' => 'require',
        'moment_img' => 'checkImgs',
    ];

    protected $message = [
        'title' => '动态标题不能为空',
        'moment_img' => '上传文件的参数不对',
    ];

    public function checkImgs($value)
    {

            if (is_object($value)) {
                return false;
            } else {
                foreach ($value as $k => $v) {
                    $type = $v->getMime();
                    if (!in_array($type, ['image/jpeg,image/png,image/gif'])) {
                        return false;
                    }
                }
            }

        return true;
    }

}