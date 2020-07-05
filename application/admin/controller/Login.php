<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class Login extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        return $this->fetch();

    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
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
        $where = $request->post();
        $where['passwd'] = md5('dongyi'.$request->post('passwd'));
        if(empty($where)){
            $result = array(
                'msg'=>'请完善您的登陆信息',
                'err'=>'2'
            );
        }else{
            $res = Db::table('shop')->where($where)->find();
            if($res){
                Session::set('adminInfo',$res);
                $result = array(
                    'msg'=>'登陆成功',
                    'err'=>'0',
                    'data'=>$res,
                );
            }else{
                $result = array(
                    'msg'=>'请核实您的登陆信息',
                    'err'=>'1',
                );
            }
        }
        return json($result);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
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
}
