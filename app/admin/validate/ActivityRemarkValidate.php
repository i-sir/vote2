<?php

namespace app\admin\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"ActivityRemark",
    *     "name_underline"   =>"activity_remark",
    *     "table_name"       =>"activity_remark",
    *     "validate_name"    =>"ActivityRemarkValidate",
    *     "remark"           =>"备注管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-08 16:53:34",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, ActivityRemark);
    * )
    */

class ActivityRemarkValidate extends Validate
{

protected $rule = [];




protected $message = [];




//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',

//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
