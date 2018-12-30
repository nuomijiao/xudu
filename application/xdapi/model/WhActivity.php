<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/3
 * Time: 15:40
 */

namespace app\xdapi\model;


use app\lib\enum\ActivityTypeEnum;

class WhActivity extends BaseModel
{
    protected $hidden = [

    ];

    public function getMainImgAttr($value)
    {
        return config('setting.domain').$value;
    }

    public function province()
    {
        return $this->belongsTo('WhRegion', 'province_id', 'id');
    }


    public function city()
    {
        return $this->belongsTo('WhRegion', 'city_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo('WhRegion', 'district_id', 'id');
    }

    public function myCollect()
    {
        return $this->hasMany('WhActCollect', 'act_id', 'id');
    }

    public static function getHotActivity($page, $size)
    {
        return self::with([
            'city' => function($query) {
                $query->field(['name', 'shortname', 'id']);
            }
        ])->where('is_hot', '=', 1)->where('start_time', '>', time())->field(['id', 'act_name', 'act_ad_price', 'start_time', 'city_id', 'join_number', 'main_img', 'address'])->order('join_number', 'desc')->paginate($size, true, ['page' => $page]);
    }

    public static function getActDetail($id, $uid)
    {
        return self::with([
            'province' => function($query) {
                $query->field(['name', 'shortname', 'id']);
            }
        ])->with([
            'city' => function($query) {
                $query->field(['name', 'shortname', 'id']);
            }
        ])->with([
            'district' => function($query) {
                $query->field(['name', 'shortname', 'id']);
            }
        ])->with([
            'myCollect' => function ($query) use ($uid) {
                $query->where('user_id', '=', $uid);
            }
        ])->where('id', '=', $id)->find();
    }

    public function getActAttachAttr($value, $data)
    {
        return unserialize($value);

    }

    public static function getBrief($id)
    {
        return self::field(['id','act_name','act_ad_price','act_ch_price','act_ad_member_price', 'act_ch_member_price', 'main_img', 'start_time', 'act_attach'])->where('id', '=', $id)->find();
    }

    public static function getActivityByCat($id, $page, $size, $type, $searchkey)
    {
        $where = [];
        if (ActivityTypeEnum::Hot == $type) {
            $where['is_hot'] = ['=', ActivityTypeEnum::Hot];
        }
        $where['act_name'] = ['like', "%".$searchkey."%"];
        if ($id) {
            $where['cat_id'] = ['=', $id];
        }

        return self::where($where)->where('start_time','>', time())->field(['id', 'act_name', 'act_ad_price', 'start_time', 'city_id', 'main_img'])->paginate($size, true, ['page' => $page]);

    }




}