<?php

namespace initmodel;

/**
    * @AdminModel(
    *     "name"             =>"Video",
    *     "name_underline"   =>"video",
    *     "table_name"       =>"video",
    *     "model_name"       =>"VideoModel",
    *     "remark"           =>"视频管理",
    *     "author"           =>"",
    *     "create_time"      =>"2025-10-07 17:20:31",
    *     "version"          =>"1.0",
    *     "use"              => new \initmodel\VideoModel();
    * )
    */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class VideoModel extends Model{

	protected $name = 'video';//视频管理

	//软删除
	protected $hidden            = ['delete_time'];
	protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
