<?php

namespace app\api\controller;

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
    public function index(Request $request)
    {
        $request = Request::instance();
        $domain = $request->domain();
        $data = array();

        if($request->get('order')==1){
            $order='asc';//升序
        }else{
            $order='desc'; //降序
        }
        if($request->get('filter')==NULL){
            $data = Db::table('staff')->select();
        }elseif ($request->get('filter')=='create_time'){//时间排序
            $data = $data = Db::table('staff')->order("st_id {$order}")->select();
        }elseif ($request->get('filter')=='status'){//状态排序
            $data = $data = Db::table('staff')->order("status {$order}")->select();
        }elseif ($request->get('filter')=='distance'){//距离排序
            //当前位置 116.111529,35.79448
        }elseif ($request->get('filter')=='order_number'){//订单数量
            $data = $data = Db::table('staff')->order("order_number {$order}")->select();
        }else{
            $data = Db::table('staff')->select();
        };
        $result['err']=200;
        $result['msg']='信息获取成功';
        foreach ($data as $k=>$v){
            $data[$k]['pic_1'] = $domain.'/uploads/'.$v['pic_1'];
        }
        $result['data']=$data;
        return $result;
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id,Request $request)
    {

    }
}
