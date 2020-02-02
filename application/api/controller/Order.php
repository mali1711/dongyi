<?php

namespace app\api\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class Order extends Controller
{
    public function _initialize()
    {
        Session::set('users.user_id',3);
    }
    
    /**
     * 显示所有订单列表
     *
     * @return \think\Response
     */
    public function getindex()
    {
        //
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function getcreate()
    {
        //
    }

    /**
     * 保存用户提交的订单信息
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function postsave(Request $request)
    {
        //
        //$data = $request->post();

        $data['user_id'] = 3;
        $data['st_id'] = $request->post('st_id');
        $data['pr_id'] = $request->post('pr_id');
        $data['subtime'] = strtotime(date('Y').'-'.$request->post('subtime'),time());//预约时间
        $st_info = Db::table('staff')->where('st_id',$data['st_id'])->find();
        $pr_info = Db::table('project')->where('pr_id',$data['pr_id'])->find();
        $data['st_info'] = json_encode($st_info);
        $data['pr_info'] = json_encode($pr_info);
        $data['add_purchase_num'] = 0;//加购数量
        $data['add_purchase_price'] = $pr_info['add_purchase_price'];//加购单价
        $data['add_purchase_tprice'] = $data['add_purchase_num']*$data['add_purchase_price'];//加购总价
        $data['subsidy'] = 0;//路费补助
        $data['total_price'] = $pr_info['price'];//订单总价,不含加价
        $res = Db::table('order')->insert($data);
        if($res){
            $result = array(
                'msg'=>'添加成功',
                'err'=>0,
            );
        }else{
            $result = array(
                'msg'=>'添加失败',
                'err'=>1,
            );
        }

        return $result;
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function getread(Request $request)
    {
        $where['user_id'] = Session::get('users.user_id');
        $where['status'] = $request->get('status');
        $result = Db::table('order')->where($where)->select();
        return $result;
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
    }
}
