<?php


namespace app\xdapi\controller\v1;

use app\lib\exception\ActivityException;
use app\xdapi\controller\BaseController;
use app\xdapi\model\WhActivity;


class Activity extends BaseController
{

    public function getActivityList(){
        $activity = WhActivity::getAcivity();
        if ($activity->isEmpty()) {
            throw new ActivityException();
        }
        $this->success_return($activity);
    }
}