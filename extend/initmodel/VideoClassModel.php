<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"VideoClass",
    *     "name_underline"   =>"video_class",
    *     "table_name"       =>"video_class",
    *     "model_name"       =>"VideoClassModel",
    *     "remark"           =>"分类列表",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-07 17:20:46",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\VideoClassModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class VideoClassModel extends Model{

	protected $name = 'video_class';//分类列表

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
