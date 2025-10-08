<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"Sign",
    *     "name_underline"   =>"sign",
    *     "table_name"       =>"sign",
    *     "validate_name"    =>"SignValidate",
    *     "remark"           =>"签到管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-08 15:49:31",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, Sign);
    * )
    */

class SignValidate extends Validate
{

protected $rule = [];




protected $message = [];





//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',


//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
