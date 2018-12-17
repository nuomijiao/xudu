<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/12
 * Time: 18:36
 */

namespace app\xdapi\model;


class WhMomentsDis extends BaseModel
{
    protected $autoWriteTimestamp = true;

    protected $updateTime = false;

    protected $hidden = [
        'user_id', 'to_user_id',
    ];

    public function getCreateTimeAttr($value)
    {
        return date('Y年m月d日', $value);
    }

    public function fromUser()
    {
        return $this->belongsTo('WhUser', 'user_id', 'id');
    }

    public function toUser()
    {
        return $this->belongsTo('WhUser', 'to_user_id', 'id');
    }

    public static function getCommentsById($id)
    {
        $comments = WhMomentsDis::with([
            'fromUser' => function($query) {
                $query->field(['id', 'user_name', 'head_img']);
            }
        ])->with([
            'toUser' => function($query) {
                $query->field(['id', 'user_name']);
            }
        ])->where('moment_id', '=', $id)->order('create_time', 'asc')->select();
        return $comments;
    }
}