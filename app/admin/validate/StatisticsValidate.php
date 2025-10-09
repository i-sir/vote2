<?php

namespace app\admin\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"Statistics",
    *     "name_underline"   =>"statistics",
    *     "table_name"       =>"statistics",
    *     "validate_name"    =>"StatisticsValidate",
    *     "remark"           =>"统计管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-09 10:20:26",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, Statistics);
    * )
    */

class StatisticsValidate extends Validate
{

protected $rule = [];




protected $message = [];




//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',

//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
