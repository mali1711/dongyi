<?php

namespace app\api\controller;

use think\captcha\Captcha;
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
                'msg'=>'信息不完整',
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
                    'msg'=>'密码错误',
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

    /**
     * 注册
     */
    public function postregistration(Request $request)
    {
        $data = $request->post('');
        $where['mobile'] = $data['mobile'];
        $user = Db::table('users')->where($where)->find();
        if($user){
            return returnApi(10004,'手机号已经注册','');
        }
        $data['passwd'] = md5('dongyi'.$request->post('passwd'));
        $res = Db::table('users')->insert($data);
        if($res){
           return returnApi(0,'注册成功','');
        }else{
            return returnApi(10003,'注册失败','');
        }
    }
    
    /**
     * 展示验证码
     * @return \think\Response
     */
    public function getcaptcha()
    {

        $captcha = new Captcha();
        $captcha->length   = 300;
        $captcha->useNoise = false;
        return $captcha->entry();
    }

    /**
     * 验证用户输入的验证码是否正确
     */
    public function postCaptchaResult(Request $request){
        $captcha = $request->post('captcha');
        if (!captcha_check($captcha)){
            $result = array(
                'err'=>10002,
                'c'=>$captcha,
                'msg'=>'验证码输入错误'
            );
        }else{
            $result = array(
                'err'=>0,
                'c'=>$captcha,
                'msg'=>'验证码输入成功'
            );
        }
        return $result;
    }


}
