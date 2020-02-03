<?php

namespace app\api\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class Login extends Controller{

    /**
     * 登陆
     */
    public function postLogin(Request $request)
    {
        $where = $request->post();
        $where['passwd'] = md5('dongyi'.$request->post('passwd'));
        if(empty($where)){
            $result = array(
                'msg'=>'请正确填写你的登陆信息',
                'err'=>'2'
            );
        }else{
            $res = Db::table('users')->where($where)->find();
            if($res){
                Session::set('users',$res);
                $result = array(
                    'msg'=>'登陆成功',
                    'err'=>'0',
                    'data'=>$res,
                );
            }else{
                $result = array(
                    'msg'=>'账号或者密码错误',
                    'err'=>'1',
                );
            }
        }
        return json($result);
    }
}
