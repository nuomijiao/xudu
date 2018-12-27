<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/27
 * Time: 10:03
 */

namespace app\xdapi\controller\v1;

use app\xdapi\controller\BaseController;
use app\xdapi\model\WhRegion;
use app\xdapi\service\Character as CharacterService;

class Character extends BaseController
{
    public function getCityList()
    {
        $cityList = WhRegion::getCity();
        $city = CharacterService::groupByInitials($cityList->toArray(), 'pinyin');
        return $this->xdreturn($city);
    }
}