<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"ActivityRemark",
 *     "name_underline"          =>"activity_remark",
 *     "controller_name"         =>"ActivityRemark",
 *     "table_name"              =>"activity_remark",
 *     "remark"                  =>"备注管理"
 *     "api_url"                 =>"/api/wxapp/activity_remark/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-10-08 16:53:34",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\ActivityRemarkController();
 *     "test_environment"        =>"http://vote2.ikun:9090/api/wxapp/activity_remark/index",
 *     "official_environment"    =>"http://xcxkf220.aubye.com/api/wxapp/activity_remark/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class ActivityRemarkController extends AuthController
{

    //public function initialize(){
    //	//备注管理
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/activity_remark/index
     * http://xcxkf220.aubye.com/api/wxapp/activity_remark/index
     */
    public function index()
    {
        $ActivityRemarkInit  = new \init\ActivityRemarkInit();//备注管理   (ps:InitController)
        $ActivityRemarkModel = new \initmodel\ActivityRemarkModel(); //备注管理   (ps:InitModel)

        $result = [];

        $this->success('备注管理-接口请求成功', $result);
    }


    /**
     * 备注管理 列表
     * @OA\Post(
     *     tags={"备注管理"},
     *     path="/wxapp/activity_remark/find_remark_list",
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
     *    @OA\Parameter(
     *         name="log_id",
     *         in="query",
     *         description="记录id",
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
     *   test_environment: http://vote2.ikun:9090/api/wxapp/activity_remark/find_remark_list
     *   official_environment: http://xcxkf220.aubye.com/api/wxapp/activity_remark/find_remark_list
     *   api:  /wxapp/activity_remark/find_remark_list
     *   remark_name: 备注管理 列表
     *
     */
    public function find_remark_list()
    {
        $ActivityRemarkInit  = new \init\ActivityRemarkInit();//备注管理   (ps:InitController)
        $ActivityRemarkModel = new \initmodel\ActivityRemarkModel(); //备注管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        if ($params["keyword"]) $where[] = ["remark", "like", "%{$params['keyword']}%"];
        if ($params["log_id"]) $where[] = ["log_id", "=", $params["log_id"]];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $ActivityRemarkInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $ActivityRemarkInit->get_list_paginate($where, $params);
        if (empty($result)) $this->error("暂无信息!");

        $this->success("请求成功!", $result);
    }


    /**
     * 备注管理 详情
     * @OA\Post(
     *     tags={"备注管理"},
     *     path="/wxapp/activity_remark/find_remark",
     *
     *
     *
     *    @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="id",
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
     *   test_environment: http://vote2.ikun:9090/api/wxapp/activity_remark/find_remark
     *   official_environment: http://xcxkf220.aubye.com/api/wxapp/activity_remark/find_remark
     *   api:  /wxapp/activity_remark/find_remark
     *   remark_name: 备注管理 详情
     *
     */
    public function find_remark()
    {
        $ActivityRemarkInit  = new \init\ActivityRemarkInit();//备注管理    (ps:InitController)
        $ActivityRemarkModel = new \initmodel\ActivityRemarkModel(); //备注管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $ActivityRemarkInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }
 


}
