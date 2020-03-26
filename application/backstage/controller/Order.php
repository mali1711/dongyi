<?php

namespace app\backstage\controller;

use app\backstage\model\Orders;
use app\backstage\model\Staffs;
use think\Controller;
use think\Request;

class Order extends Controller{

    /*
     * 获取订单列表
     */
    public function getindex(Request $request)
    {
        //
        $where = $request->get('');
        $order = new Orders();
        if($request->get('status')=='all'){
            $result = $order->all();
        }else{
            $result = $order->where($where)->select();
        }
        foreach ($result as $k=>$value){
            $result[$k]->st_info = json_decode($value->st_info);
            $result[$k]->pr_info = json_decode($value->pr_info);
            $result[$k]->subtime = date('Y-m-d H:i',$value->subtime);
        }
        if(empty($result)){
            return returnApi('10001','没有数据','');
        }else{
            return returnApi('0','数据获取成功',$result);
        }

    }

    /**
     * 获取订单详情信息
     */
    public function getdetail(Request $request)
    {
      $data = Orders::get($request->get('id'));
      $data['subtime'] = date('Y-m-d H:i',$data['subtime']);
      if(empty($data)){
          return returnApi('10001','没有数据','');
      }else{
          return returnApi('0','数据获取成功',$data);
      }
    }

    /**
     *
     */
    public function posteditStaff(Request $request)
    {
        //获取当前选择的技师信息
        $st_info = json_encode(Staffs::get($request->post('st_id'))->toArray());
        $where['order_id'] = $request->post('order_id');
        //获取订单原来的技师信息
        $data['st_info'] = $st_info;
        $data['st_id'] = $request->post('st_id');
        $data['st_info_ord'] = Orders::get($request->post('order_id'))->st_info;
        $order = new Orders;
        $res = $order->save($data,$where);
        if($res){
            return returnApi('0','修改成功','');
        }else{
            return returnApi('10002','更改失败','');
        }

    }


}
