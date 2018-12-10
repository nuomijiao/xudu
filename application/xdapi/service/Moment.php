<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/9
 * Time: 15:53
 */

namespace app\xdapi\service;


use app\xdapi\model\WhImage;
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
            array_push($imgurl, config('setting.domain').DS.$data['url']);
            array_push($imgarr, $data);
        }
        Db::startTrans();
        try {
            $img_ids = '';
            foreach ($imgarr as $k => $v) {
                $image = WhImage::create($v);
                $img_ids .= $image->id.",";
            }
            $img_id = rtrim($img_ids, ',');
            WhMoments::create([
                'title' => $title,
                'imgs' => $img_id,
                'location' => $addr,
                'user_id' => $uid,
            ]);
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