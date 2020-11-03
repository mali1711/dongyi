<?php

namespace app\common\controller;

use think\Controller;
use think\Request;
use Qiniu\Storage\UploadManager;
use Qiniu\Auth;
class Upload extends Controller
{

    protected $accessKey = 'ySCej83DBr7F6AJvWbJVVi0qtr1BAAgGzR4IFZxB';

    protected $secretKey = 'VOUu7YsBGmAGPDkiB9GHcuZDzX8fdizY1k6VIV84';

    /**
     * @var string
     * 空间存储名称
     */
    protected $bucket = 'sir6';
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
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
        $uploadMgr = new UploadManager();
        $auth = new Auth($accessKey, $secretKey);
        $token = $auth->uploadToken($bucket);
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
