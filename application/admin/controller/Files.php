<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

/**
 * 文件处理函数
 * Class Files
 * @package app\admin\controller
 */
class Files extends Controller{

    public function postupload(Request $request)
    {
        $res = uploadImg('pic','project');
        $path = 'project/'.$res->getSaveName();
        $data = array(
          'err'=>0,
          'src'=>$path
        );
        return json($data);
    }


}
