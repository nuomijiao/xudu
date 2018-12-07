<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/10/24 0024
 * Time: 0:27
 */

namespace app\xdapi\controller\v1;

use app\lib\exception\ActivityMissException;
use app\xdapi\controller\BaseController;


class Activity extends BaseController
{

    public function getBanner(){
        $banner = WhBanner::getBanner();
        if ($banner->isEmpty()) {
            throw new ActivityMissException();
        }
        $this->success_return($banner);
    }
}