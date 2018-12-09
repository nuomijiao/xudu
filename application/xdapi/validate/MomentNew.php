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
        'moment_img' => 'checkImg',
    ];

    protected $message = [
        'title' => '动态标题不能为空',
        'moment_img' => '上传文件参数错误',
    ];

    public function checkImg($value)
    {
        echo "<pre>";
        print_r($value);
        echo "</pre>";die;
        if (count($value['name'])) {
            $imgarr = [];
            foreach ($value as $kk => $vv) {
                foreach ($vv as $k => $v) {
                    $imgarr[$k][$kk] = $v;
                }
            }

            foreach ($imgarr as $k => $v) {
                if (!in_array($v['type'], ['jpg', 'png', 'gif', 'JPG', 'PNG', 'GIF'])){
                    return false;
                }
                if (!in_array(mime_content_type($value['name']), ['image/jpeg','image/png','image/gif'])) {
                    return false;
                }
            }

        }
        return true;
    }
}