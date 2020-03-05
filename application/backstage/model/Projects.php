<?php

namespace app\backstage\model;

use think\Model;

class Projects extends Model
{
    //
    protected $table="project";
    protected $pk = "pr_id";
    protected $dateFormat=false;//关闭时间戳自动转换
}
