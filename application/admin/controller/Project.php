<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;

class Project extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $list = Db::table('project')->order('pr_id','desc')->select();
        $this->assign('list',$list);
        return $this->fetch('index');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        return $this->fetch('addProject');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
        $data = $request->post();
        $res = $this->uploadImg('file','project');
        $data['photo'] = 'project/'.$res->getSaveName();
        $data['create_time'] = date('Y-m-d H:i:s');
        $result = Db::table('project')->insert($data);
        if($result){
            return 0;
        }else{
            return 1;
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {

    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $data = DB::table('project')->find($id);
        $this->assign($data);
        return $this->fetch('editProject');

    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->put();
        if ($data['photo']==''){
            unset($data['photo']);
        }
        unset($data['pic']);
        unset($data['_method']);
        $res = Db::table('project')
            ->where('pr_id', $id)
            ->update($data);
        return $res;
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
        $res = Db::table('project')->delete($id);
        if($res){
            $result = array(
                'err'=>0,
                'info'=>'删除成功'
            );
        }else{
            $result = array(
                'err'=>1,
                'info'=>'删除失败'
            );
        }

        return json($result);
    }

    function uploadImg($file='file',$path='default')
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
