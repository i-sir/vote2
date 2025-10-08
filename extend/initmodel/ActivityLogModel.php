<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"ActivityLog",
    *     "name_underline"   =>"activity_log",
    *     "table_name"       =>"activity_log",
    *     "model_name"       =>"ActivityLogModel",
    *     "remark"           =>"报名记录",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-07 17:49:34",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\ActivityLogModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class ActivityLogModel extends Model{

	protected $name = 'activity_log';//报名记录

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
