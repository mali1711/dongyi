<?php

namespace app\Api\controller;

use sms\ucpaas\Ucpaas;
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
        $options['accountsid']='594c1c503e4539df437ba99c235eb0ec';
        //填写在开发者控制台首页上的Auth Token
        $options['token']='eb78c6b89891c71f8769ce7909c39a25';
        $upass = new Ucpaas($options);
        $appid = "2c021c71e4a04741842f836df509b957";	//应用的ID，可在开发者控制台内的短信产品下查看
        $templateid = "277877";    //可在后台短信产品→选择接入的应用→短信模板-模板ID，查看该模板ID
        $uid = "";

//70字内（含70字）计一条，超过70字，按67字/条计费，超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。

        return $upass->SendSms($appid,$templateid,$param,$mobile,$uid);
    }
}
