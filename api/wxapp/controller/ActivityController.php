<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"Activity",
 *     "name_underline"          =>"activity",
 *     "controller_name"         =>"Activity",
 *     "table_name"              =>"activity",
 *     "remark"                  =>"活动管理"
 *     "api_url"                 =>"/api/wxapp/activity/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-10-07 17:37:28",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\ActivityController();
 *     "test_environment"        =>"http://vote2.ikun:9090/api/wxapp/activity/index",
 *     "official_environment"    =>"http://xcxkf220.aubye.com/api/wxapp/activity/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class ActivityController extends AuthController
{

    //public function initialize(){
    //	//活动管理
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/activity/index
     * http://xcxkf220.aubye.com/api/wxapp/activity/index
     */
    public function index()
    {
        $ActivityInit  = new \init\ActivityInit();//活动管理   (ps:InitController)
        $ActivityModel = new \initmodel\ActivityModel(); //活动管理   (ps:InitModel)

        $result = [];

        $this->success('活动管理-接口请求成功', $result);
    }


    /**
     * 活动管理 列表
     * @OA\Post(
     *     tags={"活动管理"},
     *     path="/wxapp/activity/find_activity_list",
     *
     *
     *
     *
     *    @OA\Parameter(
     *         name="openid",
     *         in="query",
     *         description="openid",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="(选填)关键字搜索",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *     @OA\Parameter(
     *         name="is_paginate",
     *         in="query",
     *         description="false=分页(不传默认分页),true=不分页",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *
     *   test_environment: http://vote2.ikun:9090/api/wxapp/activity/find_activity_list
     *   official_environment: http://xcxkf220.aubye.com/api/wxapp/activity/find_activity_list
     *   api:  /wxapp/activity/find_activity_list
     *   remark_name: 活动管理 列表
     *
     */
    public function find_activity_list()
    {
        $ActivityInit  = new \init\ActivityInit();//活动管理   (ps:InitController)
        $ActivityModel = new \initmodel\ActivityModel(); //活动管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ['is_show', '=', 1];
        if ($params["keyword"]) $where[] = ["name", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $ActivityInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $ActivityInit->get_list_paginate($where, $params);
        if (empty($result)) $this->success("暂无信息!",[]);

        $this->success("请求成功!", $result);
    }


    /**
     * 活动管理 详情
     * @OA\Post(
     *     tags={"活动管理"},
     *     path="/wxapp/activity/find_activity",
     *
     *
     *
     *    @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="id 可不传,不传默认一个",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *   test_environment: http://vote2.ikun:9090/api/wxapp/activity/find_activity
     *   official_environment: http://xcxkf220.aubye.com/api/wxapp/activity/find_activity
     *   api:  /wxapp/activity/find_activity
     *   remark_name: 活动管理 详情
     *
     */
    public function find_activity()
    {
        $ActivityInit  = new \init\ActivityInit();//活动管理    (ps:InitController)
        $ActivityModel = new \initmodel\ActivityModel(); //活动管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where = [];
        if ($params['id']) {
            $where[] = ["id", "=", $params["id"]];
        } else {
            $where[] = ["is_show", "=", 1];
        }


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $ActivityInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


}
