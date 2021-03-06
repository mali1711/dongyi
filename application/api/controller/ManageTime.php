<?php

namespace app\api\controller;

use think\Controller;
use think\Db;
use think\Request;

class ManageTime extends Controller{
    //获取日期
    public function getInitial(Request $request)
    {
        $cuttime = time();
        $time = $this->_gettimeToday();
        for ($i=0;$i<=5;$i++){
            $cuttdate[] = date("m-d",$cuttime+$i*24*60*60);
        }
        $data = array();
        $data['date'] = $cuttdate;
        $data['time'] = $time;
        return $data;
    }

    /**
     * 用户通过点击获取时间
     */
    public function getclickTime(Request $request)
    {
       $data = $request->get('date');
       $st_id = $request->get('st_id');
       $date = date('Y-').$data;
       if ($date==date('Y-m-d')){
           $time = $this->_gettimeToday();
       }else{
           $time = $this->_gettimeToOtherDay($date);
       }
        $busytime = $this->getLockTime($st_id,$data);
        foreach ($busytime as $k=>$v){
            $busytime[$k] = date('H',strtotime($v));
        }
        $newtime = [];
        foreach ($time as $key=>$value){

            if(!in_array($value,$busytime)){
                $newtime[] = $value;
            }
        }
        return $newtime;
    }

    public function getLockTime($st_id,$time)
    {
        $startTime = date('Y').'-'.$time;
        $endTime = date('Y-m-d',strtotime($startTime)+86400);
        $timelist1 = Db::table('staff_managetime')
            ->where('st_id',$st_id)
            ->where('lockingtime','between time',[$startTime,$endTime])
            ->column('lockingtime');//获取锁定的时间
        $startTime = strtotime($startTime);
        $endTime = strtotime($endTime);
        $timelist2 = Db::table('order')
            ->where('st_id',$st_id)
            ->where('subtime','between time',[$startTime,$endTime])
            ->column('subtime');//获取锁定的时间
        foreach ($timelist2 as $k=>$v){
            $timelist2[$k] = date('Y-m-d H:00:00',$v);
        }
        return array_merge($timelist1,$timelist2);
    }

    /**
     *
     * @param $stId 技师id
     * @param $startTime 开始时间
     * @param $lockTime 结束时间
     * @author mali
     * @date 2020/9/30 11:33 下午
     */
    static public function loctTime($stId,$startTime,$lockTime)
    {
        $data = array();
        for ($i=0;$i<$lockTime;$i++){
            $data[$i]['st_id'] = $stId;
            $data[$i]['lockingtime'] = date('Y-m-d H:i:s',$startTime + (3600*($i+1)));
            $data[$i]['create_time'] = date('Y-m-d H:i:s');
        }
        Db::table('staff_managetime')->insertAll($data);
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
     * 时间管理，主要是查看技师目前已经被占用的时间
     */
    public function gettimecontrol()
    {
        $where = array(
            'st_id'=>40,
        );
        Db::table('order')->where($where)->where('create_time','between time',['2015-1-1','2016-1-1']);
    }

    /**
     *
     * @param $st_id 用户id
     * @param $startTime 开始时间(时间戳)
     * @param int $time 被锁时间长度（半个小时一个单位）
     * @author mali
     * @date 2020/9/28 2:27 下午
     */
    static function octTime($st_id='',$startTime='',$Locktime='')
    {
        $data = array();
        $st_id = 1;
        $startTime = time();
        for ($i=1;$i<=$Locktime;$i++){
            $data[$i]['st_id'] = $st_id;
            $data[$i]['lockingtime'] = date('Y-m-d H:i:s',($startTime+30*$Locktime));
            $data[$i]['create_time'] = date('Y-m-d H:i:s');
        }
        $res =  Db::table('staff_managetime')->insertAll($data);
    }
}
