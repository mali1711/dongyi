<?php

namespace app\common\controller;

use sms\ucpaas\Ucpaas;
use think\Config;
use think\Controller;
use think\Request;
class Sms extends Controller{

    public function postsendmsg(Request $request)
    {
        $param = rand(100000,999999);
        $mobile = $request->post('mobile');
        $res = array();
        //$res = $this->sendmsg($param,$mobile);
        if($mobile==''){
            $result = array(
                'err'=>10001,
                'msg'=>'请填写您的手机号',
                'data'=>$res
            );
        }else{
            $result = array(
                'err'=>0,
                'code'=>$param,
                'data'=>$res
            );
        }
        return $result;
    }

    /**
     * @param $param 验证码
     * @param $mobile 手机号
     * @return mixed|string
     */
    protected function sendmsg($param,$mobile)
    {
        $options['accountsid']=Config::get('ucpaas.accountsid');
        //填写在开发者控制台首页上的Auth Token
        $options['token']=Config::get('ucpaas.token');
        $upass = new Ucpaas($options);
        $appid = Config::get('ucpaas.appid');	//应用的ID，可在开发者控制台内的短信产品下查看
        $templateid = "277877";    //可在后台短信产品→选择接入的应用→短信模板-模板ID，查看该模板ID
        $uid = "";

//70字内（含70字）计一条，超过70字，按67字/条计费，超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。

        return $upass->SendSms($appid,$templateid,$param,$mobile,$uid);
    }
}
