<?php
/**
 * 订单中心
 */
namespace app\staff\controller;

use app\staff\model\Orders;
use think\Controller;
use think\Db;
use think\Request;

class Order extends Controller{

    /**
     * 订单列表
     * @param Request $request
     */
    public function getList(Request $request)
    {
        $where['st_id'] = $request->get('st_id');
        $where['status'] = $request->get('status');
        if ($where['status']==''){
            unset($where['status']);
        }
        $list = Orders::where($where)->order('create_time','desc')->select()->toArray();
        foreach ($list as $k=>$v){
            $list[$k]['subtime'] = date('Y-m-d H:i:s',$v['subtime']);
        }
        if($list){
            return returnApi('0','订单信息获取成功',$list);
        }else{
            return returnApi('10007','订单信息获取失败','');
        }
    }

    /**
     * 接单
     * @param Request $request
     */
    public function getreceipt(Request $request)
    {
        $orders = Orders::get($request->get('order_id'));
        if(!$orders){
            return returnApi('10008','此订单有误，请核实','');
        }
        $orders->status = 1;
        $res = $orders->save();
        if($res){
            return returnApi('0','您已成功接单，请尽快完成','');
        }else{
            return returnApi('10007','接单有误,请重新操作或者联系客服','');
        }
    }


    /**
     * 订单详情
     * @param Request $request
     */
    public function getdetail(Request $request)
    {
        $result = Orders::get($request->get('order_id'));
        $result['st_info'] = json_decode($result['st_info']);
        $result['pr_info'] = json_decode($result['pr_info']);
        return $result;
    }
    
    /**
     * 订单通知
     * @param Request $request
     */
    public function getnotice(Request $request)
    {
        $result = Orders::get($request->get());
        if($result){
            $result->notice = 0;
            $result->save();
            return returnApi('0','您有新的订单了','');
        }else{
            return returnApi('10010','您没有新的订单','');
        }
    }
}
