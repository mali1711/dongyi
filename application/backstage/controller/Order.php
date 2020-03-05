<?php

namespace app\backstage\controller;

use app\backstage\model\Orders;
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
      if(empty($data)){
          return returnApi('10001','没有数据','');
      }else{
          return returnApi('0','数据获取成功',$data);
      }
    }


}
