<?php
/**
 * 个人中心
 */
namespace app\staff\controller;

use app\common\controller\Sms as commonSms;
use think\Db;
use think\Request;

class Staff extends commonSms {

    /**
     * 登陆
     * @param Request $request
     */
    public function postLogin(Request $request)
    {
        $where = $request->post('');
        $where['passwd'] = md5('dongyi'.$request->post('passwd'));
        $staffInfo = Db::table('staff')->where($where)->find();
        if($staffInfo){
           return returnApi('0','登陆成功',$staffInfo);
        }else{
           return returnApi('10001','输入有误',$staffInfo);
        }
    }


    /**
     * 找回密码
     * @param Request $request
     */
    public function postforget(Request $request)
    {
        $where['iphone'] = $request->post('iphone');
        $data['passwd'] = md5('dongyi'.$request->post('passwd'));
        $res = Db::table('staff')->where($where)->update($data);
        if($res){
            return returnApi('0','密码修改成功','');
        }else{
            return returnApi('10002','密码修改失败','');
        }
    }

    /**
     * 暂停服务
     * @param Request $request
     */
    public function getleave(Request $request)
    {
        $where = $request->get('');
        $data['status']=0;
        $res = Db::table('staff')->where($where)->update($data);
        if($res){
            return returnApi('0','休假成功','');
        }else{
            return returnApi('10003','休假失败','');
        }
    }

    /**
     * 结束休假
     * @param Request $request
     */
    public function getleaveOff(Request $request)
    {
        $where = $request->get('');
        $data['status']=1;
        $res = Db::table('staff')->where($where)->update($data);
        if($res){
            return returnApi('0','结束休假成功','');
        }else{
            return returnApi('10004','结束休假失败','');
        }
    }

    /**
     * 查看用户信息
     * @param Request $request
     */
    public function getshow(Request $request)
    {
        $res = Db::table('staff')->where($request->get(''))->find();
        return returnApi(0,'信息获取成功',$res);
    }

    /**
     * 修改个人信息
     * @param Request $request
     */
    public function postsave(Request $request)
    {
        $where['st_id'] = $request->post('st_id');
        $data = $request->post('');
        unset($data['st_id']);
        $res = Db::table('staff')->where($where)->update($data);
        if ($res){
            return returnApi('0','用户信息更新成功','');
        }else{
            return returnApi('10005','用户信息更新成功失败','');
        }
    }
}
