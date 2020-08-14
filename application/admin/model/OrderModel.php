<?php

namespace app\admin\model;

use think\Model;

class OrderModel extends Model
{
    //
    protected $table = 'order';

    public function setSubtimeAttr($value)
    {
        return strtotime($value);
    }
}
