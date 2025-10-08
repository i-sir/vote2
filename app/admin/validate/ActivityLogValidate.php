<?php

namespace app\admin\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"ActivityLog",
    *     "name_underline"   =>"activity_log",
    *     "table_name"       =>"activity_log",
    *     "validate_name"    =>"ActivityLogValidate",
    *     "remark"           =>"报名记录",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-07 17:49:34",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, ActivityLog);
    * )
    */

class ActivityLogValidate extends Validate
{

protected $rule = [];




protected $message = [];




//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',

//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
