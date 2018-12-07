<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/10/24 0024
 * Time: 0:27
 */

namespace app\xdapi\controller\v1;

use app\lib\exception\BannerException;
use app\xdapi\controller\BaseController;
use app\xdapi\model\WhBanner;


class Banner extends BaseController
{

    public function getBannerList(){
        $banner = WhBanner::getBanner();
        if ($banner->isEmpty()) {
            throw new BannerException();
        }
        $this->success_return($banner);
    }
}