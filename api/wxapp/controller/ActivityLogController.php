<?php

namespace api\wxapp\controller;

/**
 * @ApiController(
 *     "name"                    =>"ActivityLog",
 *     "name_underline"          =>"activity_log",
 *     "controller_name"         =>"ActivityLog",
 *     "table_name"              =>"activity_log",
 *     "remark"                  =>"报名记录"
 *     "api_url"                 =>"/api/wxapp/activity_log/index",
 *     "author"                  =>"",
 *     "create_time"             =>"2025-10-07 18:01:58",
 *     "version"                 =>"1.0",
 *     "use"                     => new \api\wxapp\controller\ActivityLogController();
 *     "test_environment"        =>"http://vote2.ikun:9090/api/wxapp/activity_log/index",
 *     "official_environment"    =>"http://xcxkf220.aubye.com/api/wxapp/activity_log/index",
 * )
 */


use think\facade\Db;
use think\facade\Log;
use think\facade\Cache;


error_reporting(0);


class ActivityLogController extends AuthController
{

    protected $appCode = 'cfb3747ec9fd4d2087f312fbec843b97';
    //public function initialize(){
    //	//报名记录
    //	parent::initialize();
    //}


    /**
     * 默认接口
     * /api/wxapp/activity_log/index
     * http://xcxkf220.aubye.com/api/wxapp/activity_log/index
     */
    public function index()
    {
        $ActivityLogInit  = new \init\ActivityLogInit();//报名记录   (ps:InitController)
        $ActivityLogModel = new \initmodel\ActivityLogModel(); //报名记录   (ps:InitModel)

        $result = [];

        $this->success('报名记录-接口请求成功', $result);
    }


    /**
     * 报名记录 列表
     * @OA\Post(
     *     tags={"报名记录"},
     *     path="/wxapp/activity_log/find_log_list",
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
     *
     *
     *    @OA\Parameter(
     *         name="is_me",
     *         in="query",
     *         description="true  自己报名",
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
     *         name="status",
     *         in="query",
     *         description="状态:状态:1进行中,2已结束",
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
     *   test_environment: http://vote2.ikun:9090/api/wxapp/activity_log/find_log_list
     *   official_environment: http://xcxkf220.aubye.com/api/wxapp/activity_log/find_log_list
     *   api:  /wxapp/activity_log/find_log_list
     *   remark_name: 报名记录 列表
     *
     */
    public function find_log_list()
    {
        //$this->checkAuth();

        $ActivityLogInit  = new \init\ActivityLogInit();//报名记录   (ps:InitController)
        $ActivityLogModel = new \initmodel\ActivityLogModel(); //报名记录   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ['id', '>', 0];
        if ($params['is_me']) $where[] = ['user_id', '=', $this->user_id];
        if ($params["keyword"]) $where[] = ["number|username", "like", "%{$params['keyword']}%"];
        if ($params["activity_id"]) $where[] = ["activity_id", "=", $params["activity_id"]];
        if ($params["status"]) $where[] = ["status", "=", $params["status"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段
        if ($params['is_paginate']) $result = $ActivityLogInit->get_list($where, $params);
        if (empty($params['is_paginate'])) $result = $ActivityLogInit->get_list_paginate($where, $params);
        if (empty($result)) $this->success("暂无信息!", []);

        $this->success("请求成功!", $result);
    }


    /**
     * 报名记录 详情
     * @OA\Post(
     *     tags={"报名记录"},
     *     path="/wxapp/activity_log/find_log",
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
     *   test_environment: http://vote2.ikun:9090/api/wxapp/activity_log/find_log
     *   official_environment: http://xcxkf220.aubye.com/api/wxapp/activity_log/find_log
     *   api:  /wxapp/activity_log/find_log
     *   remark_name: 报名记录 详情
     *
     */
    public function find_log()
    {
        $ActivityLogInit  = new \init\ActivityLogInit();//报名记录    (ps:InitController)
        $ActivityLogModel = new \initmodel\ActivityLogModel(); //报名记录   (ps:InitModel)

        /** 获取参数 **/
        $params            = $this->request->param();
        $params["user_id"] = $this->user_id;

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "api";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $ActivityLogInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        $this->success("详情数据", $result);
    }


    /**
     * 报名记录  添加
     * @OA\Post(
     *     tags={"报名记录"},
     *     path="/wxapp/activity_log/add_log",
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
     *
     *
     *    @OA\Parameter(
     *         name="username",
     *         in="query",
     *         description="姓名",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="gender",
     *         in="query",
     *         description="性别 文字",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="birth",
     *         in="query",
     *         description="出生年月日  2025-04-06",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="nation",
     *         in="query",
     *         description="民族",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="religion",
     *         in="query",
     *         description="宗教信仰",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="id_number",
     *         in="query",
     *         description="身份证",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="phone",
     *         in="query",
     *         description="联系方式",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="location",
     *         in="query",
     *         description="所在地",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="track",
     *         in="query",
     *         description="报名赛道",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="interests",
     *         in="query",
     *         description="兴趣爱好",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="emotion",
     *         in="query",
     *         description="情感状态",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="dance",
     *         in="query",
     *         description="演舞经验",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="song",
     *         in="query",
     *         description="擅长的歌曲/特色",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="tag",
     *         in="query",
     *         description="给自己贴个标签",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="account",
     *         in="query",
     *         description="平台账号",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="image",
     *         in="query",
     *         description="照片",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="video",
     *         in="query",
     *         description="视频",
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *   test_environment: http://vote2.ikun:9090/api/wxapp/activity_log/add_log
     *   official_environment: http://xcxkf220.aubye.com/api/wxapp/activity_log/add_log
     *   api:  /wxapp/activity_log/add_log
     *   remark_name: 报名记录  添加
     *
     */
    public function add_log()
    {
        $this->checkAuth();

        $ActivityLogInit  = new \init\ActivityLogInit();//报名记录    (ps:InitController)
        $ActivityLogModel = new \initmodel\ActivityLogModel(); //报名记录   (ps:InitModel)

        /** 获取参数 **/
        $params              = $this->request->param();
        $params["user_id"]   = $this->user_id;
        $params['order_num'] = $this->get_num_only();

        //验证身份证号是否正确
        if (!$this->validateIdCard($params["id_number"])) $this->error("身份证号格式错误!");


        // 1.不可以报名多次
        $map       = [];
        $map[]     = ['user_id', '=', $this->user_id];
        $map[]     = ['activity_id', '=', $params["activity_id"]];
        $is_attend = $ActivityLogModel->where($map)->count();
        if ($is_attend) $this->error("您已经报名过了!");

        // 2.年龄限制50岁 , 50岁以上不可以报名
        $age_restriction = cmf_config('age_restriction'); //年龄限制
        $age             = $this->calculateAgeFromIdCard($params["id_number"]);
        if ($age > $age_restriction) $this->error("超过设定{$age_restriction}岁,请勿再报名!");
        $params['age'] = $age;


        // 3.身份证和名字一致,直接审核通过
        $card_result = $this->verifyIdCard($params['id_number'], $params['username'], $this->appCode);

        if ($card_result) {
            // 处理验证结果
            if (!$card_result['result']['isok']) $this->error("身份证验证失败!");// 验证失败


            //身份证扩展信息
            $IdCardInfor = $card_result['result']['IdCardInfor'];
            if ($IdCardInfor['sex'] != $params['gender']) $this->error("性别不匹配!");
            if (strtotime($IdCardInfor['birthday']) != strtotime($params['birth'])) $this->error("出生日期不匹配!");
        } else {
            $this->error("失败请联系管理员!");
        }


        //生成序列号
        $max_number       = $ActivityLogModel->where('activity_id', $params["activity_id"])->max('number') ?? 0;
        $params["number"] = $max_number + 1;


        //查找省市区code
        $province_info = Db::name('region')->where('name', '=', $params['province'])->find();
        $city_info     = Db::name('region')->where('name', '=', $params['city'])->find();
        $county_info   = Db::name('region')->where('name', '=', $params['county'])->find();
        //name
        $params['province'] = $province_info['name'];
        $params['city']     = $city_info['name'];
        $params['county']   = $county_info['name'];
        //id
        $params['province_id'] = $province_info['id'];
        $params['city_id']     = $city_info['id'];
        $params['county_id']   = $county_info['id'];
        //code
        $params['province_code'] = $province_info['code'];
        $params['city_code']     = $city_info['code'];
        $params['county_code']   = $county_info['code'];

        /** 提交更新 **/
        $result = $ActivityLogInit->api_edit_post($params);
        if (empty($result)) $this->error("失败请重试");


        $this->success("报名成功!");
    }


    /**
     * 报名记录  编辑
     * @OA\Post(
     *     tags={"报名记录"},
     *     path="/wxapp/activity_log/edit_log",
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
     *
     *    @OA\Parameter(
     *         name="image",
     *         in="query",
     *         description="照片",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *
     *
     *
     *    @OA\Parameter(
     *         name="video",
     *         in="query",
     *         description="视频",
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
     *     @OA\Response(response="200", description="An example resource"),
     *     @OA\Response(response="default", description="An example resource")
     * )
     *
     *   test_environment: http://vote2.ikun:9090/api/wxapp/activity_log/edit_log
     *   official_environment: http://xcxkf220.aubye.com/api/wxapp/activity_log/edit_log
     *   api:  /wxapp/activity_log/edit_log
     *   remark_name: 报名记录  编辑
     *
     */
    public function edit_log()
    {
        $this->checkAuth();

        $ActivityLogInit  = new \init\ActivityLogInit();//报名记录    (ps:InitController)
        $ActivityLogModel = new \initmodel\ActivityLogModel(); //报名记录   (ps:InitModel)

        /** 获取参数 **/
        $params = $this->request->param();


        $map   = [];
        $map[] = ['id', '=', $params['id']];


        /** 提交更新 **/
        $result = $ActivityLogInit->api_edit_post($params, $map);
        if (empty($result)) $this->error("失败请重试");


        $this->success("操作成功!");
    }


    /**
     * 从身份证号计算年龄
     * @param string $idCard 身份证号码
     * @return int|false 年龄，失败返回false
     */
    function calculateAgeFromIdCard($idCard)
    {


        // 从身份证号中提取出生日期（第7-14位）
        $birthYear  = substr($idCard, 6, 4);
        $birthMonth = substr($idCard, 10, 2);
        $birthDay   = substr($idCard, 12, 2);

        // 获取当前日期
        $currentYear  = date('Y');
        $currentMonth = date('m');
        $currentDay   = date('d');

        // 计算年龄
        $age = $currentYear - $birthYear;

        // 如果当前月份小于出生月份，年龄减1
        if ($currentMonth < $birthMonth) {
            $age--;
        } // 如果月份相同但日期小于出生日，年龄减1
        elseif ($currentMonth == $birthMonth && $currentDay < $birthDay) {
            $age--;
        }

        return $age;
    }


    /**
     * 验证身份证号码是否有效
     * @param string $id 身份证号码
     * @return bool 验证结果，true为有效，false为无效
     */
    function validateIdCard($id)
    {
        // 1. 检查长度是否为18位
        if (strlen($id) != 18) {
            return false;
        }

        // 2. 检查格式
        $pattern = '/^[1-9]\d{5}(18|19|20)\d{2}(0[1-9]|1[0-2])(0[1-9]|[12]\d|3[01])\d{3}[\dXx]$/';
        if (!preg_match($pattern, $id)) {
            return false;
        }

        // 3. 验证校验码
        // 加权因子
        $factors = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
        // 校验码对应值
        $checkCodes = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];

        $sum = 0;
        for ($i = 0; $i < 17; $i++) {
            $sum += intval($id[$i]) * $factors[$i];
        }

        // 计算校验码
        $checkCodeIndex = $sum % 11;
        $checkCode      = $checkCodes[$checkCodeIndex];

        // 比较校验码（不区分大小写）
        return strtoupper($id[17]) === $checkCode;
    }

    /**
     * 身份证card验证接口调用
     * @param string $cardNo   身份证号码
     * @param string $realName 真实姓名
     * @param string $appCode  阿里云AppCode
     * @return array|false 接口返回结果或false
     */
    public function verifyIdCard($cardNo, $realName, $appCode)
    {
        // 接口配置
        $host   = "https://zidv2.market.alicloudapi.com";
        $path   = "/idcard/VerifyIdcardv2";
        $method = "GET";

        // 构建请求头
        $headers = [
            "Authorization:APPCODE " . $appCode
        ];

        // 构建查询参数
        $querys = http_build_query([
            'cardNo'   => $cardNo,
            'realName' => $realName
        ]);

        // 完整请求URL
        $url = $host . $path . "?" . $querys;

        // 初始化curl
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_URL            => $url,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_FAILONERROR    => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,
            // HTTPS设置
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ]);

        // 执行请求
        $response = curl_exec($curl);

        // 错误处理
        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);
            // 可以记录日志
            $this->error("接口调用失败: " . $error);
            return false;
        }

        // 关闭连接
        curl_close($curl);

        // 分离响应头和响应体
        list($header, $body) = explode("\r\n\r\n", $response, 2);

        // 解析JSON响应
        $result = json_decode($body, true);

        return $result;
    }
}
