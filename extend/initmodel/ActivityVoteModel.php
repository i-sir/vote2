<?php

namespace initmodel;

/**
 * @AdminModel(
 *     "name"             =>"ActivityVote",
 *     "name_underline"   =>"activity_vote",
 *     "table_name"       =>"activity_vote",
 *     "model_name"       =>"ActivityVoteModel",
 *     "remark"           =>"投票记录",
 *     "author"           =>"",
 *     "create_time"      =>"2025-10-07 17:55:11",
 *     "version"          =>"1.0",
 *     "use"              => new \initmodel\ActivityVoteModel();
 * )
 */


use think\facade\Db;
use think\Model;
use think\model\concern\SoftDelete;


class ActivityVoteModel extends Model
{

    protected $name = 'activity_vote';//投票记录

    //软删除
    protected $hidden            = ['delete_time'];
    protected $deleteTime        = 'delete_time';
    protected $defaultSoftDelete = 0;
    use SoftDelete;
}
