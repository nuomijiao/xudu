<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/12
 * Time: 17:54
 */

namespace app\xdapi\validate;


class CommentNew extends BaseValidate
{
    protected  $rule = [
        'id' => 'require|isPositiveInteger',
        'content' => 'require'
    ];

    protected $message = [
        'id' => 'id必须是正整数',
        'content' => '评论内容不能为空'
    ];
}