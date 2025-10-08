<?php

namespace api\wxapp\validate;

use think\Validate;


/**
    * @AdminModel(
    *     "name"             =>"ActivityVote",
    *     "name_underline"   =>"activity_vote",
    *     "table_name"       =>"activity_vote",
    *     "validate_name"    =>"ActivityVoteValidate",
    *     "remark"           =>"投票记录",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-07 17:55:11",
    *     "version"          =>"1.0",
    *     "use"              =>   $this->validate($params, ActivityVote);
    * )
    */

class ActivityVoteValidate extends Validate
{

protected $rule = [];




protected $message = [];





//软删除(delete_time,0)  'action'     => 'require|unique:AdminMenu,app^controller^action,delete_time,0',


//    protected $scene = [
//        'add'  => ['name', 'app', 'controller', 'action', 'parent_id'],
//        'edit' => ['name', 'app', 'controller', 'action', 'id', 'parent_id'],
//    ];


}
