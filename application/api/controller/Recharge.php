<?php

namespace app\api\controller;

use think\Controller;
use think\Db;
use think\Request;

class Recharge extends Controller{
    public function postsave()
    {
        
    }

    /**
     * 添加充值订单
     * @param array $data
     * @return int|string
     */
    public function insert($data = array())
    {
        echo 111;

    }
    
}