<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;

class Order extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        if($request->get('status')==NULL){
            $list = Db::table('order')->paginate(6);
        }else{
            $where['status'] = $request->get('status');
            $list = Db::table('order')->where($where)->paginate(6)->appends($where);
        }

        $data = $list->items();
        foreach ($list->items() as $k=>$v){
            $data[$k]['st_info'] = json_decode($v['st_info']);
            $data[$k]['pr_info'] = json_decode($v['pr_info']);
        }
        $this->assign('data', $data);
        $this->assign('list', $list);
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
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
        $list = Db::table('order')->find($id);
        $list['st_info'] = json_decode($list['st_info']);
        $list['pr_info'] = json_decode($list['pr_info']);
        $list['subtime'] = date('Y-m-d H:i:s',$list['subtime']);
        $this->assign('list', $list);
        return $this->fetch();
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
        echo 1111;
        $this->fetch();
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
    }
}
