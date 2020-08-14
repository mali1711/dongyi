<?php

namespace app\admin\controller;

use app\admin\model\OrderModel;
use app\admin\model\StaffModel;
use app\base\controller\Base;
use think\Controller;
use think\Db;
use think\Request;
use \app\admin\model\Staff;

class Order extends Base
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
        $list = Db::table('order')->find($id);
        $list['st_info'] = json_decode($list['st_info']);
        $list['pr_info'] = json_decode($list['pr_info']);
        $slist = StaffModel::field('st_id,name,nickname')->select();
        $list['subtime'] = date('Y-m-d H:i:s',$list['subtime']);
        $this->assign('slist', $slist);
        $this->assign('list', $list);
        return $this->fetch();
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

        $update = $request->put();
        $orderUpdate = OrderModel::get($id);
        $update['st_info'] = json_encode(StaffModel::get($update['st_id']));
        $update['st_info_ord'] = $orderUpdate->st_info;
        $update['notice'] = 1;
        $update['order_id'] = $id;
        $res = OrderModel::update($update);
        if($res){
            return self::showReturnCodeWithOutData(1001);
        }else{
            return self::showReturnCodeWithOutData(1002);
        }


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
