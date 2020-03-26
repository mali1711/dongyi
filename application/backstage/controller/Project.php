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
        $project = new Projects;
        $where = $request->get('');
        $data = $request->post('');
        $data['create_time'] = date('Y-m-d H:i:s');
        if($request->get('pr_id')){
            // 修改项目
            $file = request()->file('file');
            if($file!=null){//如果有上传图片
                $upres = $this->uploadImg('file','project');
                $data['photo'] = 'project/'.$upres->getSaveName();
            }
          $res =  $project->save($data,$where);
          if($res){
              return returnApi(0,'修改成功','');
          }else{
              return returnApi(10002,'操作失败','');
          }
        }else{
            // 新增项目
            $file = request()->file('file');
            if($file!=null){//如果有上传图片
                $upres = $this->uploadImg('file','project');
                $data['photo'] = 'project/'.$upres->getSaveName();
            }
            $res = $project->save($data);
            if($res){
                return returnApi(0,'新增成功',$res);
            }else{
                return returnApi(10002,'操作失败','');

            }
        }
    }

    public function getdel(Request $request)
    {
        $res = Projects::destroy($request->get('pr_id'));
        if($res){
            return returnApi('0','删除成功',$res);
        }else{
            return returnApi('10005','删除失败',$res);
        }
    }

    /**
     * 图片上传
     * @param string $file
     * @param string $path
     * @return false|string|\think\File
     */
    function uploadImg($file='file',$path='project')
    {
        $file = request()->file($file);
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . $path);
        if($info){
            // 成功上传后 获取上传信息
            // 输出 jpg
            //echo $info->getExtension();
            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
            return $info;
            // 输出 42a79759f284b767dfcb2a0197904287.jpg
            //echo $info->getFilename();
        }else{
            // 上传失败获取错误信息
            return $file->getError();
        }
    }
}
