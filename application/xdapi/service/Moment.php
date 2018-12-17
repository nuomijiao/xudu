<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/9
 * Time: 15:53
 */

namespace app\xdapi\service;


use app\lib\exception\MomentsException;
use app\xdapi\model\WhFriends;
use app\xdapi\model\WhMomentImage;
use app\xdapi\model\WhMoments;
use app\xdapi\model\WhMomentsDis;
use app\xdapi\model\WhMomentsZan;
use think\Db;
use think\Exception;

class Moment extends Picture
{

    public static function releaseMoment($uid, $img, $title, $addr) {
        $imgarr = [];
        $imgurl = [];
        foreach ($img as $key => $value) {
            $data = self::uploadImg($value, 'images');
            array_push($imgurl, config('setting.domain').DS."images".DS.$data['url']);
            array_push($imgarr, $data);
        }
        Db::startTrans();
        try {
            $moment = WhMoments::create([
                'title' => $title,
                'location' => $addr,
                'user_id' => $uid,
            ]);
            $moment_id = $moment->id;
            foreach ($imgarr as $k => &$v) {
                $v['moment_id'] = $moment_id;
                WhMomentImage::create($v);
            }
            Db::commit();
            $data = [
                'title' => $title,
                'location' => $addr,
                'img' => $imgurl,
            ];
            return $data;
        } catch(Exception $ex) {
            Db::rollback();
            throw $ex;
        }
    }

    public static function dealZan($id, $uid)
    {
        $zan = WhMomentsZan::checkZanExist($id, $uid);
        Db::startTrans();
        try {
            if ($zan && $zan->delete_time > 0) {
                //点赞delete_time = 0，赞+1
                $is_zan = WhMomentsZan::update(['id'=>$zan->id, 'delete_time' => 0]);
                Db::name('wh_moments')->where('id', '=', $id)->setInc('zan_number');
            } elseif ($zan && $zan->delete_time == 0) {
                //取消赞delete_time = time()， 赞-1
                $is_zan = WhMomentsZan::update(['id'=>$zan->id, 'delete_time' => time()]);
                Db::name('wh_moments')->where('id', '=', $id)->setDec('zan_number');
            } elseif (!$zan) {
                //点赞 create一条数据,赞+1
                $is_zan = WhMomentsZan::create([
                    'moment_id' => $id,
                    'user_id' => $uid,
                    'delete_time' => 0
                ]);
                Db::name('wh_moments')->where('id', '=', $id)->setInc('zan_number');
            }
            Db::commit();
        } catch(Exception $ex) {
            Db::rollback();
            throw $ex;
        }
        return $is_zan;

    }

    public static function checkOperateComment($momentId, $uid)
    {
        $moment = WhMoments::get($momentId);
        if (!$moment) {
            throw new MomentsException();
        }
        if ($moment->user_id == $uid) {
            throw new MomentsException([
                'msg' => '不能评论自己的动态',
                'errorCode' => 70003
            ]);
        }
        $friend = WhFriends::checkIsFriends($uid, $moment->user_id);
        if (!$friend) {
            throw new MomentsException([
                'msg' => '对方不是好友不能评论',
                'errorCode' => 70004,
            ]);
        }
        return $moment;
    }

    public static function checkOperateReply($commentId, $uid)
    {
        $comment = WhMomentsDis::get($commentId);
        if (!$comment) {
            throw new MomentsException([
                'msg' => '要回复的评论不存在',
                'errorCode' => 70005,
            ]);
        }
        if ($comment->to_user_id != $uid) {
            throw new MomentsException([
                'msg' => '不能评论非自己动态的评论',
                'errorCode' => 70006,
            ]);
        }
        return $comment;
    }

    public static function addComment($uid, $id, $content, $toUserId, $pid = 0)
    {
        Db::startTrans();
        try {
            $comment = WhMomentsDis::create([
                'moment_id' => $id,
                'user_id' => $uid,
                'content' => $content,
                'pid' => $pid,
                'to_user_id' => $toUserId,
            ]);
            //评论数加1
            Db::name('wh_moments')->where('id', '=', $id)->setInc('dis_number');
            Db::commit();
        } catch(Exception $ex) {
            Db::rollback();
            throw $ex;
        }
        return $comment;
    }

    public static function getComments($id)
    {
        $comments = WhMomentsDis::getCommentsById($id);
        $comm = [];
        foreach ($comments->toArray() as $key => $value) {
            if ($value['pid'] == 0) {
                $comm[$value['id']] = $value;
            } else {
                $comm[$value['pid']]['child'] = $value;
            }
        }
        return $comm;
    }
}