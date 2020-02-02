<?php

namespace app\api\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class Address extends Controller{

    public function getindex()
    {
        $res = $this->_find();
        if(empty($res)){
            $result = array(
                'err'=>1,
                'msg'=>'地址为空',
            );
        }else{
            $result = array(
                'err'=>1,
                'msg'=>'地址为空',
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
        $res = $this->_find();
        $data = $request->post();
        if(empty($res)){
            return $this->_insert($data);
        }else{
            return $this->_update($data);
        }
    }

    /**
     * 修改地址
     * @param Request $request
     */
    public function _update($data)
    {
        $where['user_id'] = Session::get('users.user_id');
        $res = Db::table('address')->where($where)->update($data);
        return $res;
    }

    /**
     * 添加地址
     * @param Request $request
     */
    public function _insert($data)
    {
        $data['user_id'] = Session::get('users.user_id');
        $res = Db::table('address')->insert($data);
        return $res;
    }

    public function _find()
    {

        $where['user_id'] = Session::get('users.user_id');
        $res = Db::table('address')->where($where)->find();
        return $res;
    }
}
