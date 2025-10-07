<?php

namespace app\admin\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"Video",
    *     "name_underline"   =>"video",
    *     "table_name"       =>"video",
    *     "validate_name"    =>"VideoValidate",
    *     "remark"           =>"视频管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-07 17:20:31",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, Video);
    * )
    */

class VideoValidate extends Validate
{

protected $rule = [];




protected $message = [];




//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',

//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
