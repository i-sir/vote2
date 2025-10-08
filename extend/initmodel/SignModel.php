<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"Sign",
    *     "name_underline"   =>"sign",
    *     "table_name"       =>"sign",
    *     "model_name"       =>"SignModel",
    *     "remark"           =>"签到管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-08 15:49:31",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\SignModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class SignModel extends Model{

	protected $name = 'sign';//签到管理

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
