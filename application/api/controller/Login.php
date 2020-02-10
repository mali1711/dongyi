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

    /**
     * 修改密码
     */
    public function postpass(Request $request)
    {
        $where['passwd'] = md5('dongyi'.$request->post('ordpasswd'));
        $where['user_id'] = $request->post('user_id');
        $data['passwd'] = md5('dongyi'.$request->post('passwd'));
        $res = Db::table('users')->where($where)->find();
        if($res){
            $r = Db::table('users')->where($where)->update($data);
            if($r){
                $result = array(
                    'err'=>0,
                    'msg'=>'新密码修改成功'
                );
            }else{
                $result = array(
                    'err'=>2,
                    'msg'=>'意外错误，请稍后再试'
                );
            }
        }else{
            $result = array(
                'err'=>1,
                'msg'=>'密码输入错误'
            );
        }
        return $result;
    }
}
