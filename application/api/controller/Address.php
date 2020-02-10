<?php

namespace app\api\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class Address extends Controller{

    public function getindex(Request $request)
    {
        $res = $this->_find($request->get('user_id'));
        if(empty($res)){
            $result = array(
                'err'=>1,
                'msg'=>'地址为空',
            );
        }else{
            $result = array(
                'err'=>0,
                'msg'=>'地址获取成功',
                'data'=>$res
            );
        }
        return $result;
    }


    /**
     * 更改信息
     */
    public function postsave(Request $request)
    {
        $res = $this->_find($request->get('user_id'));
        $data = $request->post();
        if($res != ''){
            $resu = $this->_insert($data);
            if($resu){
                $result = array(
                    'err'=>0,
                    'msg'=>'地址添加成功',
                    'data'=>$resu
                );
            }else{
                $result = array(
                    'err'=>1,
                    'msg'=>'地址没有修改',
                );
            }
        }else{
            $resu = $this->_update($data);
                if($resu){
                    $result = array(
                        'err'=>0,
                        'msg'=>'地址变更成功',
                        'data'=>$resu
                    );
                }else{
                    $result = array(
                        'err'=>1,
                        'msg'=>'地址没有修改',
                    );
                }
        }

        return $result;
    }

    /**
     * 修改地址
     * @param Request $request
     */
    public function _update($data)
    {
        $where['user_id'] = $data['user_id'];
        unset($data['user_id']);
        $res = Db::table('address')->where($where)->update($data);
        return $res;
    }

    /**
     * 添加地址
     * @param Request $request
     */
    public function _insert($data)
    {
        $where['user_id'] = $data['user_id'];
        $res = Db::table('address')->insert($data);
        return $res;
    }

    public function _find($user_id)
    {

        $where['user_id'] = $user_id;
        $res = Db::table('address')->where($where)->find();
        return $res;
    }

}
