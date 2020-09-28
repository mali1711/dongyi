<?php

namespace app\staff\controller;

use think\Controller;
use think\Db;
use think\Request;

class TimeManagement extends Controller{


    /**
     * 获取日期列表
     * @return array
     */
    public function getInitial(Request $request)
    {
        $datas = $request->get('date');
        $dyas = date('Y-').$datas;
        $st_id = $request->get('st_id');
        $timelist = $this->_getLockTime($st_id,$dyas);
        $cuttime = time();
        $time = $this->_gettimeToday();
        for ($i=0;$i<=5;$i++){
            $cuttdate[] = date("m-d",$cuttime+$i*24*60*60);
        }
        $date = array();
        foreach ($cuttdate as $key=>$value){
            $date[$key]['day']=$value;
            $date[$key]['disabled']=true;
        }
        $t = array();
        foreach ($time as $key=>$value){
            $t[$key]['time'] = $value;
            if(in_array($dyas.' '.$value.':00:00',$timelist)){
                $t[$key]['disabled'] = false;
            }else{
                $t[$key]['disabled'] = true;
            }
        }

        $data['date'] = $date;
        $data['time'] = $t;
        return $data;
    }
    
    /**
     * 用户通过点击获取时间
     */
    public function getclickTime(Request $request)
    {
        $data = $request->get('date');
        $date = date('Y-').$data;
        $st_id = $request->get('st_id');
        $timelist = $this->_getLockTime($st_id,$date);
        if ($date==date('Y-m-d')){
            $time = $this->_gettimeToday();
        }else{
            $time = $this->_gettimeToOtherDay($date);
        }
        $result = array();
        foreach ($time as $key=>$value){
            $result[$key]['time'] = $value;
            if(in_array($date.' '.$value.':00:00',$timelist)){
                $result[$key]['disabled'] = false;
            }else{
                $result[$key]['disabled'] = true;
            }
        }
        return $result;
    }

    /**
     * 获取锁定的时间
     * @param $st_id
     * @param $time
     * @return array
     */
    protected function _getLockTime($st_id,$time)
    {
        $s = strtotime($time);
        $startTime = date('Y-m-d',$s);
        $endTime = date('Y-m-d',$s+86400);
        $timelist = Db::table('staff_managetime')->where('st_id',$st_id)
        ->where('lockingtime','between time',[$startTime,$endTime])->column('lockingtime');
        return $timelist;
    }

    /**
     * 获取当天日期
     * @param null $time
     * @return array|null
     *
     * {st_id38: "undefined", pic_1https://dongyi.sir6.cn/uploads/project/20191228/90ce28d1fb2341a1cac05bd5e37e623a.jpg: "undefined", pr_id: "40", subscribetime: "01-29 17:00"}
     */
    public function _gettimeToday()
    {
        $cuttime = time();
        $todaystart = strtotime(date("Y-m-d")."08:00:00",time());
        $todayend = strtotime(date("Y-m-d")."23:59:59",time());
        $time = array();
        for ($i=1;$i<=23;$i++){
            if($cuttime+$i*60*60<=$todayend and $cuttime+$i*60*60>=$todaystart){
                $time[] =   date("H",$cuttime+$i*60*60);
            }
        }
        return $time;
    }

    /**
     * 获取其他时间的日期
     */
    public function _gettimeToOtherDay($date)
    {
        $cuttime = strtotime($date,time());
        $todaystart = strtotime($date."08:00:00",time());
        $todayend = strtotime($date."23:59:59",time());
        for ($i=1;$i<=23;$i++){
            if($cuttime+$i*60*60<=$todayend and $cuttime+$i*60*60>=$todaystart){
                $time[] =   date("H",$cuttime+$i*60*60);
            }
        }
        return $time;
    }


    /**
     * 时间管理
     * @param Request $request
     */
    public function gettimeman(Request $request)
    {
        $time =  $request->get('time');
        $where['lockingtime'] = date('Y-').$time;
        $where['st_id'] = $request->get('st_id');
        $resu = Db::table('staff_managetime')->where($where)->find();
        if($resu){
            $res2 = Db::table('staff_managetime')->where($where)->delete();
            if($res2){
                return returnApi(10,'已解除','');
            }else{
                return returnApi(10011,'网络延迟','');
            }
        }else{
            $data = $where;
            $data['create_time'] = date('Y-m-d H:i:s');
            $res = Db::table('staff_managetime')->insert($data);
            if($res){
                return returnApi(0,'已锁定','');
            }else{
                return returnApi(10010,'网络延迟','');
            }
        }

    }

    

}
