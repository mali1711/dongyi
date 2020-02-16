<?php

namespace app\api\controller;

use think\Controller;
use think\Request;

class ManageTime extends Controller{
    //获取日期
    public function getInitial()
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
       $date = date('Y-').$data;
       if ($date==date('Y-m-d')){
           $time = $this->_gettimeToday();
       }else{
           $time = $this->_gettimeToOtherDay($date);
       }
       return $time;
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
}
