<?php

namespace app\backstage\controller;

use app\backstage\model\Projects;
use think\Controller;
use think\Request;

class Project extends Controller{

    /**
     * 项目列表
     * @param Request $request
     */
    public function getindex(Request $request)
    {
        $data =  Projects::all();
        if(empty($data)){
            return returnApi('10001','没有数据','');
        }else{
            return returnApi('0','数据获取成功',$data);
        }
    }

    /**
     * 项目详情
     */
    public function getdetail(Request $request)
    {
        $data = Projects::get($request->get('id'));
        if(empty($data)){
            return returnApi('10001','没有数据','');
        }else{
            return returnApi('0','数据获取成功',$data);
        }
    }

    /**
     * 添加或者修改项目
     */
    public function postsave(Request $request)
    {
        if($request->post('id')){
            // 修改项目
            echo '11111';

        }else{
            // 新增项目
            echo "22222";
        }
    }
}
