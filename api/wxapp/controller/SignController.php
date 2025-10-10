<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"Sign",
 *     "name_underline"          =>"sign",
 *     "controller_name"         =>"Sign",
 *     "table_name"              =>"sign",
 *     "remark"                  =>"签到管理"
 *     "api_url"                 =>"/api/wxapp/sign/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-10-08 15:49:31",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\SignController();
 *     "test_environment"        =>"http://vote2.ikun:9090/api/wxapp/sign/index",
 *     "official_environment"    =>"http://xcxkf220.aubye.com/api/wxapp/sign/index",
 * )
 */


use initmodel\AssetModel;
use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class SignController extends AuthController
{

    //public function initialize(){
    //	//签到管理
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/sign/index
     * http://xcxkf220.aubye.com/api/wxapp/sign/index
     */
    public function index()
    {
        $SignInit  = new \init\SignInit();//签到管理   (ps:InitController)
        $SignModel = new \initmodel\SignModel(); //签到管理   (ps:InitModel)

        $result = [];

        $this->success('签到管理-接口请求成功', $result);
    }


    /**
     * 日历列表
     * @OA\Post(
     *     tags={"签到管理"},
     *     path="/wxapp/sign/find_date_list",
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
     *         name="date",
     *         in="query",
     *         description="日期  2025-08  不穿默认当月",
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *
     *   test_environment: http://vote2.ikun:9090/api/wxapp/sign/find_date_list
     *   official_environment: http://xcxkf220.aubye.com/api/wxapp/sign/find_date_list
     *   api:  /wxapp/sign/find_date_list
     *   remark_name: 日历列表
     *
     */
    public function find_date_list()
    {
        $this->checkAuth();

        $params = $this->request->param();
        $date   = $params['date'] ?? date('Y-m');

        $result = $this->getMonthDaysDetail($date);


        $this->success("请求成功!", $result);
    }


    /**
     * 签到
     * @OA\Post(
     *     tags={"签到管理"},
     *     path="/wxapp/sign/add_sign",
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *   test_environment: http://vote2.ikun:9090/api/wxapp/sign/add_sign
     *   official_environment: http://xcxkf220.aubye.com/api/wxapp/sign/add_sign
     *   api:  /wxapp/sign/add_sign
     *   remark_name: 签到
     *
     */
    public function add_sign()
    {
        $this->checkAuth();

        $SignInit  = new \init\SignInit();//签到管理    (ps:InitController)
        $SignModel = new \initmodel\SignModel(); //签到管理   (ps:InitModel)

        //参数
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;
        $today             = date('Y-m-d'); // 当前日期
        $order_num         = $this->get_num_only();

        // 检查今天是否已经签到
        $map            = [];
        $map[]          = ['user_id', '=', $this->user_id];
        $map[]          = ['sign_date', '=', $today];
        $existingRecord = $SignModel->where($map)->find();
        if ($existingRecord) $this->error('今天已经签到过了！');

        // 获取最后一次签到记录
        $map100     = [];
        $map100[]   = ['user_id', '=', $this->user_id];
        $lastSignIn = $SignModel->where($map100)->order('sign_date desc,id desc')->find();

        if ($lastSignIn) {
            $lastDate = $lastSignIn['sign_date'];
            $daysDiff = (strtotime($today) - strtotime($lastDate)) / (60 * 60 * 24);
            if ($daysDiff == 1) {
                // 连续签到
                $consecutiveDays = $lastSignIn['consecutive_days'] + 1;
            } else {
                // 中断，重新开始
                $consecutiveDays = 1;
            }
        } else {
            // 第一次签到
            $consecutiveDays = 1;
        }

        //根据签到天数,获得对应签到积分
        $basic_points      = cmf_config('basic_points');//第一天签到得n积分
        $cumulative_points = cmf_config('cumulative_points'); //累计签到得n积分(上次累计+设定累计增加)
        $online_points     = cmf_config('online_points'); //累计积分设置上线积分
        $sign_in_cycle     = cmf_config('sign_in_cycle');   //如连续签到设定天数,会重新轮回规则

        // 计算签到积分
        $sign_points = 0;

        // 考虑周期轮回的实际连续天数
        $actualConsecutiveDays = $consecutiveDays;
        if ($sign_in_cycle > 0) {
            $actualConsecutiveDays = ($consecutiveDays - 1) % $sign_in_cycle + 1;
        }

        // 计算基础积分 + 累计积分
        $calculatedPoints = $basic_points + ($actualConsecutiveDays - 1) * $cumulative_points;

        // 应用积分上限
        if ($calculatedPoints > $online_points) {
            $sign_points = $online_points;
        } else {
            $sign_points = $calculatedPoints;
        }

        // 确保积分不为负数
        if ($sign_points < 0) {
            $sign_points = 0;
        }

        // 使用模型插入新的签到记录
        $sign_id = $SignModel->strict(false)->insert([
            'user_id'          => $this->user_id,
            'sign_date'        => $today,
            'order_num'        => $order_num,
            'consecutive_days' => $consecutiveDays,
            'balance'          => $sign_points,
            'create_time'      => time(),
        ], true);

        //签到奖励
        $remark = "操作人[签到奖励];操作说明[已连续签到{$consecutiveDays}天;签到获得积分{$sign_points}];操作类型[签到奖励];";//管理备注
        AssetModel::incAsset('签到奖励积分 [210]', [
            'operate_type'  => 'point',//操作类型，balance|point ...
            'identity_type' => 'member',//身份类型，member| ...
            'user_id'       => $this->user_id,
            'price'         => $sign_points,
            'order_num'     => $order_num,
            'order_type'    => 210,
            'content'       => '签到奖励',
            'remark'        => $remark,
            'order_id'      => $sign_id,
        ]);

        $this->success('签到成功');
    }

    /**
     * 获取指定年月的所有日期详情列表
     * @param string $yearMonth 年月格式，如：2023-10 或 202310
     * @return array 日期详情列表，格式为：
     *                          [
     *                          ['date' => '2023-10-01', 'day' => 1],
     *                          ['date' => '2023-10-02', 'day' => 2],
     *                          ...
     *                          ]
     */
    protected function getMonthDaysDetail($yearMonth)
    {
        $SignModel = new \initmodel\SignModel(); //签到管理   (ps:InitModel)

        // 处理输入格式，确保是 'YYYY-MM' 格式
        $date = date('Y-m', strtotime($yearMonth));
        if (!$date) {
            return []; // 格式错误返回空数组
        }

        // 获取该月的第一天
        $firstDay = $date . '-01';

        // 获取该月的最后一天
        $lastDay = date('Y-m-t', strtotime($firstDay));

        // 计算该月的总天数
        $totalDays = date('d', strtotime($lastDay));

        // 生成日期详情列表
        $days = [];
        for ($i = 1; $i <= $totalDays; $i++) {

            //日期
            $sign_date = date('Y-m-d', strtotime("{$date}-{$i}"));

            //获取周几（数字格式：1=周一，7=周日）
            $weekday = date('N', strtotime($sign_date));

            //是否签到
            $is_sign = false;
            $map     = [];
            $map[]   = ['user_id', '=', $this->user_id];
            $map[]   = ['sign_date', '=', $sign_date];
            $sign    = $SignModel->where($map)->count();
            if ($sign) $is_sign = true;

            $days[] = [
                'date'    => $sign_date,
                'day'     => $i, // 单独的天数（数字格式）
                'weekday' => $weekday, // 周几（数字格式：1=周一，7=周日）
                'is_sign' => $is_sign,//是否签到 0为未签到
            ];
        }

        return $days;
    }

}
