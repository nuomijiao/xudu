<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/12/27
 * Time: 10:03
 */

namespace app\xdapi\controller\v1;

use app\lib\exception\CityException;
use app\xdapi\controller\BaseController;
use app\xdapi\model\WhRegion;
use app\xdapi\service\Character as CharacterService;
use app\xdapi\validate\SearchCity;

class Character extends BaseController
{
    public function getCityList()
    {
        $cityList = WhRegion::getCity();
        $city = CharacterService::groupByInitials($cityList->toArray(), 'pinyin');
        return $city;
    }

    public function getCityId()
    {
        $request = (new SearchCity())->goCheck();
        $name = $request->param('name');
        $city = WhRegion::getCityId($name);
        if (!$city) {
            throw new CityException();
        }
        return $this->xdreturn($city);
    }
}