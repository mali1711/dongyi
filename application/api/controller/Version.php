<?php

namespace app\api\controller;

use app\base\controller\Base;
use think\Request;

class Version extends Base
{

    /**
     * 安卓获取最新版本
     * @param Request $request
     * @return Version|array
     */
    public function getandroid(Request $request)
    {
        $data = array(
            'latestVersion'=> '1.0.6',//最新版本
            'currentVersionStatus'=>true,//当前版本是否可用
            'updateInformation'=>'',//版本更新信息
            'androidUrl'=>'http://resources.sir6.cn/dongYang1.06.apk',//版本更新地址

        );
        return self::showReturnCode('200122',$data,'更新信息获取成功');
    }

    /**
     * IOS获取最新版本
     * @param Request $request
     * @return Version|array
     */
    public function getios(Request $request)
    {
        $data = array(
            'latestVersion'=> '9.8.13',//最新版本
            'currentVersionStatus'=>false,//当前版本是否可用
            'updateInformation'=>'',//版本更新信息
            //'iosUrl'=>'http://resources.sir6.cn/dongyi1.06.apk'//版本更新地址

        );
        return self::showReturnCode('200',$data,'更新信息获取成功');
    }

    /**
     * 获取安卓和IOS所有的最新版本信息
     * @param Request $request
     */
    public function getandroidAndIos(Request $request)
    {
        $data = array(
            'latestVersion'=> '1.0.7',//最新版本
            'currentVersionStatus'=>false,//当前版本是否可用
            'updateInformation'=>"1.订单优化",//版本更新信息
            'androidUrl'=>'http://resources.sir6.cn/dongYang1.07.apk',//版本更新地址
            'iosUrl'=>'http://resources.sir6.cn/dongyi1.0.7.apk'//版本更新地址

        );
        return self::showReturnCode('200',$data,'更新信息获取成功');
    }
}
