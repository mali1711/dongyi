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
    public function index()
    {
        $request = Request::instance();
        $domain = $request->domain();
        $data = Db::table('staff')->select();
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
    public function read($id)
    {
        if ($id=='all'){ //查询所有的技师列表
            $request = Request::instance();
            $domain = $request->domain();
            $data = Db::table('staff')->select();
            $result['err']=200;
            $result['msg']='信息获取成功';
            foreach ($data as $k=>$v){
                $data[$k]['pic_1'] = $domain.'/uploads/'.$v['pic_1'];
            }
            $result['data']=$data;
            return $result;
        }

    }
}
