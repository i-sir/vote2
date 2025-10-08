<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"Activity",
    *     "name_underline"   =>"activity",
    *     "table_name"       =>"activity",
    *     "validate_name"    =>"ActivityValidate",
    *     "remark"           =>"活动管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-07 17:37:28",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, Activity);
    * )
    */

class ActivityValidate extends Validate
{

protected $rule = ['name'=>'require',
];




protected $message = ['name.require'=>'名称不能为空!',
];





//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',


//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
