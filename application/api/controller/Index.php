<?php

namespace app\api\controller;

use app\common\controller\Api;

/**
 * 首页接口
 */
class Index extends Api
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    /**
     * 首页
     * 
     */
    public function index()
    {
        $this->success('请求成功');
    }

    //获取首页轮播图
    public function get_banner()
    {
        $this->success('请求成功','','1000');
    }
}
