<?php

namespace app\backstage\controller;

use think\Controller;
use think\Db;
use think\Request;

class Login extends Controller
{

    /**
     * 登陆接口
     */
    public function postLogin(Request $request)
    {
        $where = $request->post();
        $where['passwd'] = md5('dongyi'.$request->post('passwd'));
        if(empty($where)){
            $result = array(
                'msg'=>'信息不完整',
                'err'=>'2'
            );
        }else{
            $res = Db::table('shop')->where($where)->find();
            if($res){
                $result = array(
                    'msg'=>'登陆成功',
                    'err'=>'0',
                    'data'=>$res,
                );
            }else{
                $result = array(
                    'msg'=>'密码错误',
                    'err'=>'1',
                );
            }
        }
        return json($result);
    }

    
}
