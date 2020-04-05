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
        $lng = $request->get('lng');//当前所在位置
        $lat = $request->get('lat');//当前所在位置
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
            $data = Db::table('staff')->select();
            $data =  $this->initorderinfo($data,$lng,$lat,$order);
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
        $data = $this->initorderinfo($data,$lng,$lat,'');
        $result['data']=$data;
        return $result;
    }

    /**
     * @param $data 技师列表
     * @param $lng1 当前位置经度
     * @param $lat2 当前位置纬度
     * @param int $order 排序
     */
    public function initorderinfo($data,$lng1,$lat2,$order='')
    {
        foreach ($data as $key=>$value){
            $data[$key]['distance'] = round($this->_distance($lng1,$lat2,$value['Longitude'],$value['latitude']),1);
        }
        if($order=='desc'){//升序
            $data = $this->arraySort($data, 'distance', SORT_DESC);
        }elseif ($order=='asc'){//降序
            $data = $this->arraySort($data, 'distance', SORT_ASC);
        }else{
            $data = $data;
        }
        return $data;
    }

    function arraySort($array, $keys, $sort = SORT_DESC) {
        $keysValue = [];
        foreach ($array as $k => $v) {
            $keysValue[$k] = $v[$keys];
        }
        array_multisort($keysValue, $sort, $array);
        return $array;
    }

    /**
     * 计算距离
     * @param string $lng1 距离一的经度
     * @param string $lat1 距离一的纬度
     * @param string $lng2 距离二的经度
     * @param string $lat2 距离二的纬度
     * @return float|int
     */
    protected  function _distance($lng1='116.12848900000000000', $lat1='35.76238700000000000', $lng2='116.111529', $lat2='35.79448')
    {
        //将角度转为狐度
        $radLat1 = deg2rad($lat1);//deg2rad()函数将角度转换为弧度
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);
        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137 * 1000;
        return $s/1000;
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
