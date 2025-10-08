<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"ActivityRemark",
    *     "name_underline"   =>"activity_remark",
    *     "table_name"       =>"activity_remark",
    *     "model_name"       =>"ActivityRemarkModel",
    *     "remark"           =>"备注管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-08 16:53:34",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\ActivityRemarkModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class ActivityRemarkModel extends Model{

	protected $name = 'activity_remark';//备注管理

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
