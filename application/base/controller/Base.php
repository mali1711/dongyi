<?php
namespace app\base\controller;


use think\Controller;
use think\Response;

class Base extends Controller
{

    static public function showReturnCode($code = '', $data = [], $msg = ''){
        $return_data = [
            'code' => '500',
            'msg' => '未定义消息',
            'data' => (($code == 200)or($code == 1001)) ? $data : (object)[],
        ];
        if (empty($code)) return $return_data;
        $return_data['code'] = $code;
        if(!empty($msg)){
            $return_data['msg'] = $msg;
        }else if (isset(ReturnCode::$return_code[$code]) ) {
            $return_data['msg'] = ReturnCode::$return_code[$code];
        }
        return Response::create($return_data, 'json')->code(ReturnCode::$http_code[$code]);
    }

    static public function showReturnCodeWithOutData($code = '', $msg = '')
    {
        return self::showReturnCode($code,(object)[],$msg);
    }
}
