<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class Staff extends Common
{

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return $this->fetch('index');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        return $this->fetch('addStaff');
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
        unset($data['pic']);
        $res = Db::table('staff')->insert($data);
        if($res){
            $res = array(
                'err'=>0,
                'msg'=>'添加成功'
            );
        }else{
            $res = array(
                'err'=>1,
                'msg'=>'添加失败'
            );

        }

        return json($res);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        if($id = 'all'){
            $list = Db::table('staff')->select();
            $count = Db::table('staff')->count();
        }
        $data = array(
            'code'=>0,
            'msg'=>'',
            'count'=>$count,
            'data'=>$list
        );
        return json($data);

    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $res = Db::table('staff')->find($id);
        $this->assign($res);
        return $this->fetch('editStaff');
    }

    /**
     * 保存更新的资源
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->put();
        $data['status'] = isset($data['status'])?$data['status']:"0";
        $data['monday'] = isset($data['monday'])?$data['monday']:"";
        $data['tuesday'] = isset($data['tuesday'])?$data['tuesday']:"";
        $data['wednesday'] = isset($data['wednesday'])?$data['wednesday']:"";
        $data['thursday'] = isset($data['thursday'])?$data['thursday']:"";
        $data['friday'] = isset($data['friday'])?$data['friday']:"";
        $data['saturday'] = isset($data['saturday'])?$data['saturday']:"";
        $data['sunday'] = isset($data['sunday'])?$data['sunday']:"";
        unset($data['pic']);
        unset($data['_method']);
        $res = Db::table('staff')
            ->where('st_id', $id)
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
        return Db::table('staff')->delete($id);
    }
}
