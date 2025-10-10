<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"Video",
 *     "name_underline"          =>"video",
 *     "controller_name"         =>"Video",
 *     "table_name"              =>"video",
 *     "remark"                  =>"视频管理"
 *     "api_url"                 =>"/api/wxapp/video/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-10-07 17:20:31",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\VideoController();
 *     "test_environment"        =>"http://vote2.ikun:9090/api/wxapp/video/index",
 *     "official_environment"    =>"http://xcxkf220.aubye.com/api/wxapp/video/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class VideoController extends AuthController
{

    //public function initialize(){
    //	//视频管理
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/video/index
     * http://xcxkf220.aubye.com/api/wxapp/video/index
     */
    public function index()
    {
        $VideoInit  = new \init\VideoInit();//视频管理   (ps:InitController)
        $VideoModel = new \initmodel\VideoModel(); //视频管理   (ps:InitModel)

        $result = [];

        $this->success('视频管理-接口请求成功', $result);
    }


    /**
     * 分类列表
     * @OA\Post(
     *     tags={"视频管理"},
     *     path="/wxapp/video/find_class_list",
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *
     *   test_environment: http://vote2.ikun:9090/api/wxapp/video/find_class_list
     *   official_environment: http://xcxkf220.aubye.com/api/wxapp/video/find_class_list
     *   api:  /wxapp/video/find_class_list
     *   remark_name: 分类列表
     *
     */
    public function find_class_list()
    {
        $VideoClassInit  = new \init\VideoClassInit();//分类列表   (ps:InitController)
        $VideoClassModel = new \initmodel\VideoClassModel(); //分类列表   (ps:InitModel)

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
        $result = $VideoClassInit->get_list($where, $params);
        if (empty($result)) $this->success("暂无信息!",[]);

        $this->success("请求成功!", $result);
    }


    /**
     * 分类插架 列表
     * @OA\Post(
     *     tags={"视频管理"},
     *     path="/wxapp/video/find_class_plugin_list",
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
     *   test_environment: http://vote2.ikun:9090/api/wxapp/video/find_class_plugin_list
     *   official_environment: http://xcxkf220.aubye.com/api/wxapp/video/find_class_plugin_list
     *   api:  /wxapp/video/find_class_plugin_list
     *   remark_name: 分类插架 列表
     *
     */
    public function find_class_plugin_list()
    {
        $VideoClassInit  = new \init\VideoClassInit();//分类列表   (ps:InitController)
        $VideoClassModel = new \initmodel\VideoClassModel(); //分类列表   (ps:InitModel)

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
        $result                  = $VideoClassInit->get_plugin_list($where, $params);
        if (empty($result)) $this->success("暂无信息!",[]);

        $this->success("请求成功!", $result);
    }


    /**
     * 视频管理 列表
     * @OA\Post(
     *     tags={"视频管理"},
     *     path="/wxapp/video/find_video_list",
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
     *    @OA\Parameter(
     *         name="class_id",
     *         in="query",
     *         description="分类ID",
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
     *   test_environment: http://vote2.ikun:9090/api/wxapp/video/find_video_list
     *   official_environment: http://xcxkf220.aubye.com/api/wxapp/video/find_video_list
     *   api:  /wxapp/video/find_video_list
     *   remark_name: 视频管理 列表
     *
     */
    public function find_video_list()
    {
        $VideoInit  = new \init\VideoInit();//视频管理   (ps:InitController)
        $VideoModel = new \initmodel\VideoModel(); //视频管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        $where[] = ['is_show', '=', 1];
        if ($params["keyword"]) $where[] = ["name", "like", "%{$params['keyword']}%"];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];
        if ($params['class_id']) $where[] = ['class_id', '=', $params['class_id']];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $VideoInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $VideoInit->get_list_paginate($where, $params);
        if (empty($result)) $this->success("暂无信息!",[]);

        $this->success("请求成功!", $result);
    }


    /**
     * 视频管理 详情
     * @OA\Post(
     *     tags={"视频管理"},
     *     path="/wxapp/video/find_video",
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
     *   test_environment: http://vote2.ikun:9090/api/wxapp/video/find_video
     *   official_environment: http://xcxkf220.aubye.com/api/wxapp/video/find_video
     *   api:  /wxapp/video/find_video
     *   remark_name: 视频管理 详情
     *
     */
    public function find_video()
    {
        $VideoInit  = new \init\VideoInit();//视频管理    (ps:InitController)
        $VideoModel = new \initmodel\VideoModel(); //视频管理   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $VideoInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


}
