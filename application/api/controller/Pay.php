<?php


namespace app\api\controller;

use think\Controller;
use think\Db;
use think\Loader;
use think\Request;
use app\api\controller\Recharge;

Loader::import('alipayrsa2.AliPay');
class Pay extends Controller{
    public function _initialize()
    {

    }
    public function getpay(Request $request)
    {
        $user_id = $request->get('user_id');
        $type = 'aliplay';
        $total =  $request->get('total');
        $total = floatval($total);
        $out_trade_no = date('YmdHis').rand(1000,9999);
        $this->saveRechargeOrder($user_id,$type,$total,$out_trade_no);
        $foo = new \AliPay();
        $notify_url = 'http://dongyi.sir6.cn/api/Api/notify_url';
        $orderinfo =  $foo->topay($total,'董亿余额充值','董亿个人中心账号余额充值，详情请查看app',$out_trade_no,$notify_url);
        return $orderinfo;

    }

    /**
     * @param string $user_id 用户id
     * @param string $type 支付方式
     * @param string $money 金额
     * @param $out_trade_no 订单金额
     * @return string
     */
    public function saveRechargeOrder($user_id='',$type='aliplay',$money='',$out_trade_no)
    {
        $recData['code'] = $out_trade_no;
        $recData['user_id'] = $user_id;
        $recData['type'] = $type;
        $recData['money'] = $money;
        $recData['create_time'] = date('Y-m-d H:i:s');
        Db::table('recharge_order')->insert($recData);
        $rechargeId = Db::name('user')->getLastInsID();
        return $rechargeId;
    }

    public function postnotify_url(Request $request)
    {
        $data = $request->post('');
        $nodata['notify_data'] = json_encode($data);
        $nodata['create_time'] = date('Y-m-d H:i:s');
        $nodata['out_trade_no'] = $data['out_trade_no'];
        $nodata['receipt_amount'] = $data['receipt_amount'];
        Db::table('alipay_notify')->insert($nodata);
        if($data['trade_status']=='TRADE_SUCCESS'){
            $res = $this->getrecharge($data['out_trade_no'],$data['receipt_amount']);
            if($res){
                echo 'success';
            }else{
                echo "fail";
            }
        }else{
            echo "fail";
        }
    }

    /**
     * 充值金额
     */
    public function getrecharge($out_trade_no='',$receipt_amount='')
    {
        $user_id = Db::table('recharge_order')->field('user_id')->where('code',$out_trade_no)->find();
        $where['users_id'] = $user_id['user_id'];
        $userBlance = Db::table('balance')->where($where)->find();
        if($userBlance){
            $res = Db::table('balance')->where($where)->setInc('balance', $receipt_amount);
        }else{
            $data['balance'] = $receipt_amount;
            $data['users_id'] = $user_id['user_id'];
            $data['create_time'] = date('Y-m-d H:i:s');
            $res = Db::table('balance')->insert($data);

        }
        return $res;
    }
}
