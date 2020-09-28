<?php
/**
 * Created by PhpStorm.
 * User: mali@mali
 * Date: 2020/9/26
 * Time: 9:45 上午
 */

namespace app\api\logic;


use app\api\model\BalanceModel;
use think\Db;

class BalanceLogic
{

    protected $model = null;

    public function __construct()
    {
        $this->model = new BalanceModel();
    }
    /**
     * 余额扣除
     * @author mali
     * @date 2020/9/26 9:46 上午
     */
    static public function deductBalance($userId,$balance)
    {
        $current = Db::table('balance')->where('users_id',1)->value('balance');
        if($current<$balance)return array('code'=>3004,'msg'=>'余额不足');
        $baresult = Db::table('balance')->where('users_id',$userId)->setDec('balance',$balance);
        if($baresult){
            return array('code'=>200,'msg'=>'操作成功');
        }else{
            return array('code'=>2002,'msg'=>'操作失败');
        }
    }
}