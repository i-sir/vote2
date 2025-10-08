<?php

namespace plugins\configs\model;

/**
 * @AdminModel(
 *     "name"             =>"Configs",
 *     "name_underline"   =>"configs",
 *     "table_name"       =>"Configs",
 *     "model_name"       =>"ConfigsModel",
 *     "remark"           =>"系统配置",
 *     "author"           =>"",
 *     "create_time"      =>"2025-10-07 17:55:11",
 *     "version"          =>"1.0",
 *     "use"              => new \plugins\configs\model\ConfigsModel();
 * )
 */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class ConfigsModel extends Model
{
    protected $name = 'base_config';//投票记录

    //软删除
    protected $hidden            = ['delete_time'];
    protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
