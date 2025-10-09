<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"Notice",
    *     "name_underline"   =>"notice",
    *     "table_name"       =>"notice",
    *     "validate_name"    =>"NoticeValidate",
    *     "remark"           =>"通知管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-09 11:28:41",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, Notice);
    * )
    */

class NoticeValidate extends Validate
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
