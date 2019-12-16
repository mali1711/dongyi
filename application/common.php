<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use think\Request;
/**
 * 文件上传
 * @param string $file 默认接收的文件名
 * @param string $path 默认存放的目录
 * @return false|string|\think\File
 */

function uploadImg($file='file',$path='default')
{
    $file = request()->file($file);
    // 移动到框架应用根目录/public/uploads/ 目录下
    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . $path);
    if($info){
        // 成功上传后 获取上传信息
        // 输出 jpg
        //echo $info->getExtension();
        // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
        return $info;
        // 输出 42a79759f284b767dfcb2a0197904287.jpg
        //echo $info->getFilename();
    }else{
        // 上传失败获取错误信息
        return $file->getError();
    }
}