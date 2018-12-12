<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/9
 * Time: 15:53
 */

namespace app\xdapi\service;


use app\xdapi\model\WhMomentImage;
use app\xdapi\model\WhMoments;
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
            $img_ids = '';
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
                Db::table('wh_moments')->where('id', '=', $id)->setInc('zan_number');
            } elseif ($zan && $zan->delete_time == 0) {
                //取消赞delete_time = time()， 赞-1
                $is_zan = WhMomentsZan::update(['id'=>$zan->id, 'delete_time' => time()]);
                Db::table('wh_moments')->where('id', '=', $id)->setDec('zan_number');
            } elseif (!$zan) {
                //点赞 create一条数据,赞+1
                $is_zan = WhMomentsZan::create([
                    'moment_id' => $id,
                    'user_id' => $uid,
                ]);
                Db::table('wh_moments')->where('id', '=', $id)->setInc('zan_number');
            }
            Db::commit();
        } catch(Exception $ex) {
            Db::rollback();
            throw $ex;
        }
        return $is_zan;

    }
}