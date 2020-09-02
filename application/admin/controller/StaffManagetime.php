<?php

namespace app\admin\controller;

use app\admin\model\StaffManagetimeModel;
use app\base\controller\Base;
use think\Controller;
use think\Request;

class StaffManagetime extends Base
{
    /**
     * 显示指定用户的时间列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        //
        $tlist = $this->get_weeks();
        $this->assign('list',$tlist);
        $this->assign('st_id',$request->param('st_id'));
        return $this->fetch('staffManagetime/index');

    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
        $data = $request->param();
        if($data['lock']){
            $where['st_id'] = $data['st_id'];
            $where['lockingtime'] = $data['lockingtime'];
            $res = StaffManagetimeModel::destroy($where);
            if($res){
                return self::showReturnCodeWithOutData(1003);
            }else{
                return self::showReturnCodeWithOutData(2002);
            }
        }else{
            unset($data['lock']);
            $data['create_time'] = date('Y-m-d H:i:s');
            $res = StaffManagetimeModel::create($data);
            if($res){
                return self::showReturnCodeWithOutData(1002);
            }else{
                return self::showReturnCodeWithOutData(2002);
            }
        }
    }

    /**
     * 显示指定的资源
     * 过滤锁定的时间
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id,Request $request)
    {
        //
        $day = $request->param('day');
        $timeList = StaffManagetimeModel::getList($id,$day);
        $time = $this->_gettimeToOtherDay('2020-08-16');
        $list = array();
        foreach ($time as $k=>$v){
            $list[$k]['name'] = $v;
            $list[$k]['lock'] = false;//没有锁定时间
            $lockTime = $day.' '.$v.':00:00';
            if(in_array($lockTime,$timeList)){
                $list[$k]['lock'] = true;//已经锁定时间
            }
        }
        return self::showReturnCode(1001,$list);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }

    public function get_weeks($time = '', $format='Y-m-d')
    {
        $time = $time != '' ? $time : time();
        //组合数据
        $date = [];
        for ($i=1; $i<=7; $i++){
            $date[$i] = date($format ,strtotime( '+' . $i-1 .' days', $time));
        }
        return $date;
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
