<?php
/**
 * Created by PhpStorm.
 * User: Sweet Jiao
 * Date: 2018/10/24 0024
 * Time: 0:27
 */

namespace app\xdapi\controller\v1;

use app\lib\exception\ActivityException;
use app\xdapi\controller\BaseController;
use app\xdapi\model\WhActivity;


class Dream extends BaseController
{
    public function getActivityList(){
        $activity = WhActivity::getAcivity();
        if ($activity->isEmpty()) {
            throw new ActivityException();
        }
        $this->success_return($activity);
    }
}