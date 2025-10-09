<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"Notice",
    *     "name_underline"   =>"notice",
    *     "table_name"       =>"notice",
    *     "model_name"       =>"NoticeModel",
    *     "remark"           =>"通知管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-09 11:28:41",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\NoticeModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class NoticeModel extends Model{

	protected $name = 'notice';//通知管理

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
