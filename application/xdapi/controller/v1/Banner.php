<?php


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