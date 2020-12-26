<?php

namespace app\api\controller;

use app\base\controller\Base;
use app\staff\model\Orders;
use think\Controller;
use think\Db;
use think\Loader;
use think\Log;
use think\Request;
use think\Session;
use app\api\logic\BalanceLogic;
Loader::import('alipayrsa2.AliPay');
class Order extends Base
{

    /**
     * 保存用户提交的订单信息
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function postsave(Request $request)
    {

        //
        $data = $request->post('');
        Db::startTrans();
        $data['user_id'] = $request->post('user_id');
        $data['st_id'] = $request->post('st_id');
        $data['pr_id'] = $request->post('pr_id');
        $data['create_time'] = date('Y-m-d H:i:s');
        $data['subtime'] = strtotime(date('Y').'-'.$request->post('subtime'),time());//预约时间
        $st_info = Db::table('staff')->where('st_id',$data['st_id'])->find();
        $pr_info = Db::table('project')->where('pr_id',$data['pr_id'])->find();
        $data['st_info'] = json_encode($st_info);
        $data['pr_info'] = json_encode($pr_info);
        $data['add_purchase_num'] = 0;//加购数量
        $data['add_purchase_minimum'] = $pr_info['min_num'];//最低加购数量
        $data['title'] = $pr_info['title'];//项目名称
        $data['add_purchase_desc'] = $pr_info['add_purchase_desc'];//加购简介
        $data['add_purchase_price'] = $pr_info['add_purchase_price'];//加购单价
        $data['add_purchase_tprice'] = 0;//加购总价
        $data = array_merge($data,$this->getAddress($request->post('user_id')));
        $i = date('i',$data['subtime']);
        if($i>20){
            $data['subsidy'] = 100;//路费补助
        }else{
            $data['subsidy'] = 10;//路费补助
        }
        $data['total_price'] = $pr_info['price'];//订单总价,不含加价
        $balance = Db::table('balance')->where('users_id',$data['user_id'])->find()['balance'];
        if($balance<$data['total_price']){
            $baresult =0;
        }else{
            $baresult = Db::table('balance')->where('users_id',$data['user_id'])->setDec('balance',$data['total_price']);
        }
        if(!$baresult){
            $result = array(
                'msg'=>'余额不足',
                'err'=>10005,
            );
            Db::rollback();
        }else{
            $data['status'] = 0;
            $res = Db::table('order')->insert($data);
            ManageTime::loctTime($data['st_id'],$data['subtime'],3);
            if($res){
                Db::table('staff')->where('st_id',$data['st_id'])->setInc('order_number',1);
                $result = array(
                    'msg'=>'预约成功',
                    'err'=>0,
                );
                Db::commit();
            }else{
                $result = array(
                    'msg'=>'预约失败',
                    'err'=>10006,
                );
            }
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
        $where = $request->get();
        $data = Db::table('order')->where($where)->order('create_time','desc')->select();
        foreach ($data as $k=>$v){
            $data[$k]['subtime'] = date('Y-m-d H:i:s',$v['subtime']);
        }
        if(empty($data)){
            $result = array(
                'err'=>1,
                'msg'=>'没有订单信息',
            );
        }else{
            $result = array(
                'err'=>0,
                'msg'=>'订单信息获取成功',
                'data'=>$data
            );
        }
        return $result;
    }

    /**
     * 完成订单
     * @param Request $request
     */
    public function getcomplete(Request $request)
    {
        $orders = Orders::get($request->get('order_id'));
        if(!$orders){
            return returnApi('10008','此订单有误，请核实','');
        }
        $orders->status = 2;
        $res = $orders->save();
        if($res){
            return returnApi('0','订单完成','');
        }else{
            return returnApi('10007','接单有误,请重新操作或者联系客服','');
        }
    }

    /**
     * @param $order_id
     */
    public function getdetail(Request $request)
    {
        $where['order_id'] = $request->get('order_id');
        $res = Db::table('order')->where($where)->find();
        if($res==null)return self::showReturnCodeWithOutData(1010);
        $res['st_info'] = json_decode($res['st_info']);
        $res['pr_info'] = json_decode($res['pr_info']);
        return self::showReturnCode('200',$res);
    }

    /**
     * 加购
     * todo 扣除余额
     */
    public function postjiagou(Request $request)
    {
        Db::startTrans();
        $where['order_id'] = $request->post('order_id');
        $user_id = $request->post('user_id');
        $res = Db::table('order')->where($where)->find();
        if($res==null) return self::showReturnCodeWithOutData(1004,'订单信息不存在');
        $add_purchase_num = $request->post('add_purchase_num');//加购数量
        $total = $res['add_purchase_price']*$add_purchase_num;//加购单价 todo 需调用金额扣除接口
        $bresult = BalanceLogic::deductBalance($user_id,$total);
        if($bresult['code']!=200){
            Db::rollback();
            return self::showReturnCodeWithOutData($bresult['code']);
        }
        $res = Db::table('order')->where($where)->setInc('add_purchase_num',$add_purchase_num);
        $add_purchase_num+=$add_purchase_num;//加购完以后的数量
        if($res){
            Db::commit();
            return self::showReturnCodeWithOutData(200,'',(object)[]);
        }else{
            Db::rollback();
            return self::showReturnCodeWithOutData(2002);
        }
    }



    /**
     * 阿里支付-直接支付
     * @param Request $request
     * @author mali
     * @date 2020/9/2 10:38 上午
     */
    public function postaliPay(Request $request)
    {
        $data = $this->_initOrderInfo($request);
        return $this->_saveOrder($data);
    }

    /**
     * 保存订单
     * @author mali
     * @date 2020/9/2 5:05 下午
     */
    public function _saveOrder($data)
    {
        $data['status'] = -1;//当前状态设置成未支付
        $res = Db::table('order')->insert($data);//订单存放到数据库中

        if($res){
            $total = floatval($data['total_price']);
            //$total = 0.01;
            $out_trade_no = $data['order_number'];
            $foo = new \AliPay();
            $notify_url = Request::instance()->domain().'/api/order/aliPayNotify';
            $orderinfo =  $foo->topay($total,'董杨支付','董杨商品购买',$out_trade_no,$notify_url);
            return $orderinfo;
        }else{

        }
    }

    /**
     * 支付宝订单直接支付回调
     * @param Request $request
     * @author mali
     * @date 2020/9/2 10:40 上午
     */
    public function getaliPayNotify(Request $request)
    {
        Log::mylog('支付回调-get',$request->get(''));
        $order = new Orders();
        $data['status'] = 1;
        $where['order_number'] = $request->get('out_trade_no');
        $res = $order->save($data,$where);
        if($res){
            echo 'SUCCESS';
        }else{
            echo "ERROR";
        }
        die();
    }
    /**
     * 支付宝订单直接支付回调
     * @param Request $request
     * @author mali
     * @date 2020/9/2 10:40 上午
     */
    public function postaliPayNotify(Request $request)
    {
        Log::mylog('支付回调-post',$request->post(''));
        $order = new Orders();
        $data['status'] = 0;
        $where['order_number'] = $request->post('out_trade_no');
        $res = $order->save($data,$where);
        if($res){
            echo 'SUCCESS';
        }else{
            echo "ERROR";
        }
    }
    /**
     * 微信支付-直接付款
     * @param Request $request
     * @author mali
     * @date 2020/9/2 10:38 上午
     */
    public function wxPay(Request $request)
    {

    }

    /**
     * 支付宝订单直接支付回调
     * @param Request $request
     * @author mali
     * @date 2020/9/2 10:40 上午
     */
    public function wxPayNotify(Request $request)
    {

    }

    /**
     * 订单初始化
     * @author mali
     * @date 2020/9/2 4:40 下午
     */
    protected function _initOrderInfo($request)
    {
        $data = $request->post();
        $data['user_id'] = $request->post('user_id');
        $data['st_id'] = $request->post('st_id');
        $data['pr_id'] = $request->post('pr_id');
        $data['create_time'] = date('Y-m-d H:i:s');
        $data['subtime'] = strtotime(date('Y').'-'.$request->post('subtime'),time());//预约时间
        $st_info = Db::table('staff')->where('st_id',$data['st_id'])->find();
        $pr_info = Db::table('project')->where('pr_id',$data['pr_id'])->find();
        $data['st_info'] = json_encode($st_info);
        $data['pr_info'] = json_encode($pr_info);
        $data['add_purchase_num'] = 0;//加购数量
        $data['add_purchase_minimum'] = $pr_info['min_num'];//最低加购数量
        $data['title'] = $pr_info['title'];//项目名称
        $data['add_purchase_desc'] = $pr_info['add_purchase_desc'];//加购简介
        $data['add_purchase_price'] = $pr_info['add_purchase_price'];//加购单价
        $data['add_purchase_tprice'] = 0;//加购总价
        $data = array_merge($data,$this->getAddress($request->post('user_id')));
        $data['order_number'] = date('YmdHis').rand(100,999);
        $i = date('i',$data['subtime']);
        if($i>20){
            $data['subsidy'] = 100;//路费补助
        }else{
            $data['subsidy'] = 10;//路费补助
        }
        $data['total_price'] = $pr_info['price']+$data['subsidy'] ;//订单总价,不含加价
        return $data;
    }

    public function getAddress($user_id)
    {
        $result = Db::name('address')->where('user_id','=',$user_id)->find();
        $data['address'] = $result['address'].$result['detailed'];
        $data['address_contacts'] = $result['name'];
        $data['address_mobile'] = $result['mobile'];
        return $data;
    }

    public function getupdateOrderStatus($order_id)
    {
        $res = Db::name('order')->where('order_id','=',$order_id)->update(['status'=>2]);
        if($res){
            return self::showReturnCodeWithOutData(200,'',(object)[]);
        }else{
            return self::showReturnCodeWithOutData(300,'操作失败',(object)[]);
        }

    }
}
