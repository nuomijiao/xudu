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
use app\xdapi\model\WhTempImgs;
use think\Db;
use think\Exception;

class Moment extends Picture
{

    public static function releaseMoment($uid, $title, $addr, $ids) {
        //获取临时图片文件夹相关图片
        //更新到正式数据表
        //删除临时图片数据表数据
        //移动图片到正式文件夹
        $moment_imgs = WhTempImgs::whereIn('id',$ids)->select()->toArray();
        $new_moment_imgs = [];
        $new_imgs = [];
        $new_ids = '';
        Db::startTrans();
        try {
            $moment = WhMoments::create([
                'title' => $title,
                'location' => $addr,
                'user_id' => $uid,
            ]);
            $moment_id = $moment->id;
            foreach ($moment_imgs as $key => $value) {
                if ($value['user_id'] == $uid) {
                    array_push($new_moment_imgs, $value);
                    $new_ids .= $value['id'].",";
                    $data = [
                        'moment_id' => $moment_id,
                        'url' => DS.'images'.DS.$value['img_name'],
                    ];
                    WhMomentImage::create($data);
                }
            }
            $new_ids = rtrim($new_ids, ','). ROOT_PATH;
            WhTempImgs::destroy($new_ids);
            Db::commit();
            foreach ($new_moment_imgs as $key => $value) {
                if (!in_array(DS."images".DS.$value['img_name'], $new_imgs)) {
                    if (file_exists(ROOT_PATH.'public'.$value['img_url'])) {
                        rename(ROOT_PATH.'public'.$value['img_url'], ROOT_PATH.'public'.DS.'images'.DS.$value['img_name']);
                    }
                }
                array_push($new_imgs, DS."images".DS.$value['img_url']);
            }
            $data = [
                'title' => $title,
                'location' => $addr,
                'img' => $new_imgs,
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
                $isZan = WhMomentsZan::update(['id'=>$zan->id, 'delete_time' => 0]);
                Db::name('wh_moments')->where('id', '=', $id)->setInc('zan_number');
            } elseif ($zan && $zan->delete_time == 0) {
                //取消赞delete_time = time()， 赞-1
                $isZan = WhMomentsZan::update(['id'=>$zan->id, 'delete_time' => time()]);
                Db::name('wh_moments')->where('id', '=', $id)->setDec('zan_number');
            } elseif (!$zan) {
                //点赞 create一条数据,赞+1
                $isZan = WhMomentsZan::create([
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
        return $isZan;

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
                $comm[$value['pid']]['reply'] = $value;
            }
        }
        return array_reverse($comm);
    }

    public static function getImgs($id)
    {
        $moments_ids = WhMoments::field(['id'])->order('create_time', 'desc')->select($id)->toArray();
        $ids = '';
        foreach ($moments_ids as $key => $value) {
            $ids .= $value['id'].",";
        }
        $ids = rtrim($ids, ',');
        $imgs_url = WhMomentImage::field(['url', 'from'])->whereIn('moment_id', $ids)->limit(3)->order('id', 'desc')->select()->toArray();
        $imgs = '';
        foreach($imgs_url as $k => $v) {
            $imgs .= $v['url'].",";
        }
        $imgs = rtrim($imgs, ',');
        return $imgs;
    }



}