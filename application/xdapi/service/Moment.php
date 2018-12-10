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
}