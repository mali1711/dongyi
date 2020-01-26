<?php

namespace app\api\controller;

use think\Controller;
use think\Db;
use think\Request;

class Project extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        $list = Db::table('project')->select();
        $domain = $request->domain();
        if (empty($list)){

            $result = array(
                'err'=>404,
                'msg'=>'信息获取失败',
                'data'=>$list
            );
        }else{
            foreach ($list as $key=>$value){
                $list[$key]['photo'] = $domain.'/uploads/'.$value['photo'];
            }
            $result = array(
                'err'=>0,
                'msg'=>'信息获取成功',
                'data'=>$list
            );
        }

        return $result;
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
        $list = Db::table('project')->where('pr_id',$id)->find();
        if (empty($list)){
            $data = array(
                'err'=>1,
                'msg'=>'信息获取失败',
            );
        }else{
            $data = array(
                'err'=>0,
                'msg'=>'数据获取成功',
                'data'=>$list
            );
        }
        return $list;
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
