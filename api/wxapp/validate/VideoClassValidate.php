<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"VideoClass",
    *     "name_underline"   =>"video_class",
    *     "table_name"       =>"video_class",
    *     "validate_name"    =>"VideoClassValidate",
    *     "remark"           =>"分类列表",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-07 17:20:46",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, VideoClass);
    * )
    */

class VideoClassValidate extends Validate
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
