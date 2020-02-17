<?php

namespace app\api\controller;

use think\Controller;
use think\Db;
use think\Request;

class User extends Controller
{
    /**
     * 获取用户还有多少余额
     */
    public function getbalance(Request $request)
    {
       $res =  Db::table('balance')->where('users_id',$request->get('users_id'))->column('balance');
       if(!$res){
           return 0;
       }else{
           return $res[0];
       }
    }
}
