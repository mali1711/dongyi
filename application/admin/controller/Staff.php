<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;

class Staff extends Controller
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
        return $this->fetch('add');
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
        //
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
        //
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
