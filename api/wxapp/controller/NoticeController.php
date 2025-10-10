<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"Notice",
 *     "name_underline"          =>"notice",
 *     "controller_name"         =>"Notice",
 *     "table_name"              =>"notice",
 *     "remark"                  =>"通知管理"
 *     "api_url"                 =>"/api/wxapp/notice/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-10-09 11:28:41",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\NoticeController();
 *     "test_environment"        =>"http://vote2.ikun:9090/api/wxapp/notice/index",
 *     "official_environment"    =>"http://xcxkf220.aubye.com/api/wxapp/notice/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class NoticeController extends AuthController
{

    //public function initialize(){
    //	//通知管理
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/notice/index
     * http://xcxkf220.aubye.com/api/wxapp/notice/index
     */
    public function index()
    {
        $NoticeInit  = new \init\NoticeInit();//通知管理   (ps:InitController)
        $NoticeModel = new \initmodel\NoticeModel(); //通知管理   (ps:InitModel)

        $result = [];

        $this->success('通知管理-接口请求成功', $result);
    }


    /**
     * 通知管理 列表
     * @OA\Post(
     *     tags={"通知管理"},
     *     path="/wxapp/notice/find_notice_list",
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *
     *   test_environment: http://vote2.ikun:9090/api/wxapp/notice/find_notice_list
     *   official_environment: http://xcxkf220.aubye.com/api/wxapp/notice/find_notice_list
     *   api:  /wxapp/notice/find_notice_list
     *   remark_name: 通知管理 列表
     *
     */
    public function find_notice_list()
    {
        $NoticeInit  = new \init\NoticeInit();//通知管理   (ps:InitController)
        $NoticeModel = new \initmodel\NoticeModel(); //通知管理   (ps:InitModel)

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
        $result                  = $NoticeInit->get_list($where, $params);
        if (empty($result)) $this->success("暂无信息!",[]);

        $this->success("请求成功!", $result);
    }


    /**
     * 通知管理 详情
     * @OA\Post(
     *     tags={"通知管理"},
     *     path="/wxapp/notice/find_notice",
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
     *   test_environment: http://vote2.ikun:9090/api/wxapp/notice/find_notice
     *   official_environment: http://xcxkf220.aubye.com/api/wxapp/notice/find_notice
     *   api:  /wxapp/notice/find_notice
     *   remark_name: 通知管理 详情
     *
     */
    public function find_notice()
    {
        $NoticeInit  = new \init\NoticeInit();//通知管理    (ps:InitController)
        $NoticeModel = new \initmodel\NoticeModel(); //通知管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $NoticeInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


}
