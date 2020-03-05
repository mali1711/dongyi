<?php

namespace app\backstage\controller;

use app\backstage\model\Staffs;
use think\Controller;
use think\Request;

class Staff extends Controller{

    /**
     * 技师
     * @param Request $request
     */
    public function getindex(Request $request)
    {
        $data =  Staffs::all();
        if(empty($data)){
           return returnApi('10001','没有技师信息','');
        }else{
           return returnApi('0','信息获取成功',$data);
        }
    }

    /**
     * 技师详情
     * @param Request $request
     */
    public function getdetail(Request $request)
    {
        $data = Staffs::get($request->get('id'));
        if(empty($data)){
            return returnApi('10001','没有技师信息','');
        }else{
            return returnApi('0','信息获取成功',$data);
        }
    }
}
