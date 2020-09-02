<?php

namespace app\admin\model;

use think\Db;
use think\Model;

class StaffManagetimeModel extends Model
{
    //
    protected $table = 'staff_managetime';

    /**
     * 获取锁定时间的
     * @param $st_id
     * @param string[] $time
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author mali
     * @date 2020/8/15 3:44 下午
     */
    static public function getList($st_id,$day)
    {
        $towdDay = date("Y-m-d",strtotime("+1 day",strtotime($day)));
        $timeList = Db::table('staff_managetime')
            ->where('st_id',$st_id)
            ->where('lockingtime','between time',[$day,$towdDay])
            ->select();
        $result = array_column($timeList,'lockingtime');
        return $result;
    }
}
