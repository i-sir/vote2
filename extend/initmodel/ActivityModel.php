<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"Activity",
    *     "name_underline"   =>"activity",
    *     "table_name"       =>"activity",
    *     "model_name"       =>"ActivityModel",
    *     "remark"           =>"活动管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-07 17:37:28",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\ActivityModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class ActivityModel extends Model{

	protected $name = 'activity';//活动管理

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
