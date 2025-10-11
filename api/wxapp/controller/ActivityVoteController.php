<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"ActivityVote",
 *     "name_underline"          =>"activity_vote",
 *     "controller_name"         =>"ActivityVote",
 *     "table_name"              =>"activity_vote",
 *     "remark"                  =>"投票记录"
 *     "api_url"                 =>"/api/wxapp/activity_vote/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-10-07 17:55:11",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\ActivityVoteController();
 *     "test_environment"        =>"http://vote2.ikun:9090/api/wxapp/activity_vote/index",
 *     "official_environment"    =>"http://xcxkf220.aubye.com/api/wxapp/activity_vote/index",
 * )
 */


error_reporting(0);


class ActivityVoteController extends AuthController
{

    //public function initialize(){
    //	//投票记录
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/activity_vote/index
     * http://xcxkf220.aubye.com/api/wxapp/activity_vote/index
     */
    public function index()
    {
        $ActivityVoteInit  = new \init\ActivityVoteInit();//投票记录   (ps:InitController)
        $ActivityVoteModel = new \initmodel\ActivityVoteModel(); //投票记录   (ps:InitModel)

        $result = [];

        $this->success('投票记录-接口请求成功', $result);
    }


    /**
     * 投票列表
     * @OA\Post(
     *     tags={"投票记录"},
     *     path="/wxapp/activity_vote/find_vote_list",
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
     *         description="报名记录id",
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *
     *   test_environment: http://vote2.ikun:9090/api/wxapp/activity_vote/find_vote_list
     *   official_environment: http://xcxkf220.aubye.com/api/wxapp/activity_vote/find_vote_list
     *   api:  /wxapp/activity_vote/find_vote_list
     *   remark_name: 投票列表
     *
     */
    public function find_vote_list()
    {
        $this->checkAuth();

        $ActivityVoteInit  = new \init\ActivityVoteInit();//投票记录   (ps:InitController)
        $ActivityVoteModel = new \initmodel\ActivityVoteModel(); //投票记录   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['v.id', '>', 0];
        $where[] = ['v.log_id', '=', $params['log_id']];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        $result                  = $ActivityVoteInit->get_join_list2($where, $params);
        if (empty($result)) $this->success("暂无信息!", []);

        $this->success("请求成功!", $result);
    }


    /**
     * 我的投票记录
     * @OA\Post(
     *     tags={"投票记录"},
     *     path="/wxapp/activity_vote/my_vote_list",
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *
     *   test_environment: http://vote2.ikun:9090/api/wxapp/activity_vote/my_vote_list
     *   official_environment: http://xcxkf220.aubye.com/api/wxapp/activity_vote/my_vote_list
     *   api:  /wxapp/activity_vote/my_vote_list
     *   remark_name: 我的投票记录
     *
     */
    public function my_vote_list()
    {
        $this->checkAuth();

        $ActivityVoteInit  = new \init\ActivityVoteInit();//投票记录   (ps:InitController)
        $ActivityVoteModel = new \initmodel\ActivityVoteModel(); //投票记录   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['v.id', '>', 0];
        $where[] = ['v.user_id', '=', $this->user_id];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        $result                  = $ActivityVoteInit->get_join_list($where, $params);
        if (empty($result)) $this->success("暂无信息!", []);

        $this->success("请求成功!", $result);
    }


    /**
     * 投票添加
     * @OA\Post(
     *     tags={"投票记录"},
     *     path="/wxapp/activity_vote/add_vote",
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
     *    @OA\Parameter(
     *         name="activity_id",
     *         in="query",
     *         description="活动id",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="number",
     *         in="query",
     *         description="投票数量",
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *   test_environment: http://vote2.ikun:9090/api/wxapp/activity_vote/add_vote
     *   official_environment: http://xcxkf220.aubye.com/api/wxapp/activity_vote/add_vote
     *   api:  /wxapp/activity_vote/add_vote
     *   remark_name: 投票添加
     *
     */
    public function add_vote()
    {
        $this->checkAuth();

        $ActivityVoteInit  = new \init\ActivityVoteInit();//投票记录    (ps:InitController)
        $ActivityVoteModel = new \initmodel\ActivityVoteModel(); //投票记录   (ps:InitModel)
        $ActivityLogModel  = new \initmodel\ActivityLogModel(); //报名记录   (ps:InitModel)
        $ActivityModel     = new \initmodel\ActivityModel(); //活动管理   (ps:InitModel)

        /** 获取参数 **/
        $params              = $this->request->param();
        $params["user_id"]   = $this->user_id;
        $params["date"]      = date("Y-m-d");
        $params['order_num'] = $this->get_num_only();

        //活动是否存在
        $activity_info = $ActivityModel->where('id', '=', $params['activity_id'])->find();
        if (empty($activity_info)) $this->error("活动不存在");
        if (time() < $activity_info['attend_begin_time']) $this->error("投票未开始");
        if (time() > $activity_info['attend_end_time']) $this->error("投票已结束");

        //判断不能超过设定投票数量
        $daily_voting_count = cmf_config('daily_voting_count'); //每日投票数


        $map   = [];
        $map[] = ['user_id', '=', $this->user_id];
        $map[] = ['date', '=', date("Y-m-d")];
        $count = $ActivityVoteModel->where($map)->sum('number');
        if ($count + $params['number'] > $daily_voting_count) $this->error("每日投票数不能超过" . $daily_voting_count . "个");


        //增加投票数量
        $map100   = [];
        $map100[] = ['id', '=', $params['log_id']];
        $ActivityLogModel->where($map100)->inc('vote_number', $params['number'] ?? 1)->update();

        /** 提交更新 **/
        $result = $ActivityVoteInit->api_edit_post($params);
        if (empty($result)) $this->error("失败请重试");


        $this->success('投票成功');
    }


}
