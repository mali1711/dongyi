<?php

namespace app\backstage\model;

use think\Model;

class Orders extends Model
{
    //
    protected $pk = 'order_id';
    protected $table = 'order';
    protected $dateFormat=false;//关闭时间戳自动转换
}
