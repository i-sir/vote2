<?php

namespace app\admin\controller;


/**
 * @adminMenuRoot(
 *     "name"                =>"ActivityLog",
 *     "name_underline"      =>"activity_log",
 *     "controller_name"     =>"ActivityLog",
 *     "table_name"          =>"activity_log",
 *     "action"              =>"default",
 *     "parent"              =>"",
 *     "display"             => true,
 *     "order"               => 10000,
 *     "icon"                =>"none",
 *     "remark"              =>"报名记录",
 *     "author"              =>"",
 *     "create_time"         =>"2025-10-07 17:49:34",
 *     "version"             =>"1.0",
 *     "use"                 => new \app\admin\controller\ActivityLogController();
 * )
 */


use think\facade\Db;
use cmf\controller\AdminBaseController;


class ActivityLogController extends AdminBaseController
{

    protected $appCode = 'cfb3747ec9fd4d2087f312fbec843b97';


    // public function initialize(){
    //	//报名记录
    //	parent::initialize();
    //	}


    public function getArea()
    {
        if (cache('admin_region_list')) {
            $area = cache('admin_region_list');
        } else {
            $area = Db::name('region')->where('parent_id', '=', 10000000)->field('id,name,code')->select()->each(function ($item, $key) {
                $item['cityList'] = Db::name("region")->where(['parent_id' => $item['id']])->field('id,name,code')->select()->each(function ($item1, $key) {
                    $item1['areaList'] = Db::name("region")->where(['parent_id' => $item1['id']])->field('id,name,code')->select()->each(function ($item2, $key) {
                        return $item2;
                    });
                    return $item1;
                });
                return $item;
            });
            cache("admin_region_list", $area);
        }
        $this->success('list', '', $area);
    }

    /**
     * 首页基础信息
     */
    protected function base_index()
    {
        $ActivityInit = new \init\ActivityInit();//活动管理    (ps:InitController)
        $map          = [];
        $map[]        = ['id', '<>', 0];
        $this->assign('activity_list', $ActivityInit->get_list($map, ['order' => 'is_show,id desc']));
    }

    /**
     * 编辑,添加基础信息
     */
    protected function base_edit()
    {


        $register_track     = cmf_config('register_track'); //报名赛道
        $hobbies_list       = cmf_config('hobbies_list');//兴趣爱好
        $emotional_state    = cmf_config('emotional_state'); //情感状态
        $registration_label = cmf_config('registration_label');//报名标签


        $this->assign('register_track', $this->getParams($register_track, '/'));
        $this->assign('hobbies_list', $this->getParams($hobbies_list, '/'));
        $this->assign('emotional_state', $this->getParams($emotional_state, '/'));
        $this->assign('registration_label', $this->getParams($registration_label, '/'));


    }


    /**
     * 首页列表数据
     * @adminMenu(
     *     'name'             => 'ActivityLog',
     *     'name_underline'   => 'activity_log',
     *     'parent'           => 'index',
     *     'display'          => true,
     *     'hasView'          => true,
     *     'order'            => 10000,
     *     'icon'             => '',
     *     'remark'           => '报名记录',
     *     'param'            => ''
     * )
     */
    public function index()
    {
        $this->base_index();//处理基础信息


        $ActivityLogInit  = new \init\ActivityLogInit();//报名记录    (ps:InitController)
        $ActivityLogModel = new \initmodel\ActivityLogModel(); //报名记录   (ps:InitModel)

        $params = $this->request->param();

        /** 查询条件 **/
        $where = [];
        //$where[]=["type","=", 1];
        if ($params["keyword"]) $where[] = ["username|gender|birth|nation|id_number|phone|location|track|account", "like", "%{$params["keyword"]}%"];
        if ($params["activity_id"]) $where[] = ["activity_id", "=", $params["activity_id"]];
        if ($params["test"]) $where[] = ["test", "=", $params["test"]];


        //$where[] = $this->getBetweenTime($params['begin_time'], $params['end_time']);
        //if($params["status"]) $where[]=["status","=", $params["status"]];
        //$where[]=["type","=", 1];


        /** 查询数据 **/
        $params["InterfaceType"] = "admin";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段


        /** 导出数据 **/
        if ($params["is_export"]) $ActivityLogInit->export_excel($where, $params);


        /** 查询数据 **/
        $result = $ActivityLogInit->get_list_paginate($where, $params);


        /** 数据渲染 **/
        $this->assign("list", $result);
        $this->assign("pagination", $result->render());//单独提取分页出来
        $this->assign("page", $result->currentPage());//当前页码


        return $this->fetch();
    }


    //添加
    public function add()
    {
        $this->base_edit();//处理基础信息

        return $this->fetch();
    }


    //添加提交
    public function add_post()
    {
        $ActivityLogInit  = new \init\ActivityLogInit();//报名记录   (ps:InitController)
        $ActivityLogModel = new \initmodel\ActivityLogModel(); //报名记录   (ps:InitModel)
        $ActivityModel    = new \initmodel\ActivityModel(); //活动管理   (ps:InitModel)

        $params = $this->request->param();


        if (empty($params['username']) || empty($params['nation']) || empty($params['track']) || empty($params['interests']) || empty($params['emotion']) || empty($params['dance']) || empty($params['song']) || empty($params['tag']) || empty($params['image'])) {
            $this->error('信息不能为空');
        }

        //默认活动
        $map500                = [];
        $map500[]              = ["is_show", "=", 1];
        $params['activity_id'] = $ActivityModel->where($map500)->order('id', 'desc')->value('id');


        if (empty($params["province_code"]) || empty($params["city_code"]) || empty($params["county_code"])) $this->error("请选择地区");

        //省市区基本信息
        $province_info = Db::name('region')->where('code', '=', $params['province_code'])->find();
        $city_info     = Db::name('region')->where('code', '=', $params['city_code'])->find();
        $county_info   = Db::name('region')->where('code', '=', $params['county_code'])->find();
        //name
        $params['province'] = $province_info['name'];
        $params['city']     = $city_info['name'];
        $params['county']   = $county_info['name'];
        //id
        $params['province_id'] = $province_info['id'];
        $params['city_id']     = $city_info['id'];
        $params['county_id']   = $county_info['id'];

        //地址
        $params['location'] = "{$province_info['name']}-{$city_info['name']}-{$county_info['name']}";

        //情感状态: 其他
        if ($params['emotion'] == '其他' && $params['emotion_other']) $params['emotion'] = "其他({$params['emotion_other']})";

        //标签: 其他
        $tag = [];
        foreach ($params['tag'] as $key => $value) {
            if ($key == '其他') {
                $tag[] = "其他({$key})";
            } else {
                $tag[] = $key;
            }
        }
        $params['tag'] = $this->setParams($tag, '/');


        //生成序列号
        $max_number       = $ActivityLogModel->where('activity_id', $params["activity_id"])->max('number') ?? 0;
        $params["number"] = $max_number + 1;


        /** 增加一些判断 和前端判断校验一样 **/
        $params['order_num'] = $this->get_num_only();

        //验证身份证号是否正确
        if (!$this->validateIdCard($params["id_number"])) $this->error("身份证号格式错误!");


        // 2.年龄限制50岁 , 50岁以上不可以报名
        $age_restriction = cmf_config('age_restriction'); //年龄限制
        $age             = $this->calculateAgeFromIdCard($params["id_number"]);
        if ($age < $age_restriction) $this->error("设定{$age_restriction}岁未满足,请勿再报名!");
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


        /** 插入数据 **/
        $result = $ActivityLogInit->admin_edit_post($params);
        if (empty($result)) $this->error("失败请重试");

        $this->success("保存成功", "index{$this->params_url}");
    }


    //查看详情
    public function find()
    {
        $this->base_edit();//处理基础信息

        $ActivityLogInit  = new \init\ActivityLogInit();//报名记录    (ps:InitController)
        $ActivityLogModel = new \initmodel\ActivityLogModel(); //报名记录   (ps:InitModel)
        $params           = $this->request->param();

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "admin";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $ActivityLogInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        /** 数据格式转数组 **/
        $toArray = $result->toArray();
        foreach ($toArray as $k => $v) {
            $this->assign($k, $v);
        }

        return $this->fetch();
    }


    //查看详情
    public function video()
    {
        $this->base_edit();//处理基础信息

        $ActivityLogInit  = new \init\ActivityLogInit();//报名记录    (ps:InitController)
        $ActivityLogModel = new \initmodel\ActivityLogModel(); //报名记录   (ps:InitModel)
        $params           = $this->request->param();

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "admin";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $ActivityLogInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        /** 数据格式转数组 **/
        $toArray = $result->toArray();
        foreach ($toArray as $k => $v) {
            $this->assign($k, $v);
        }

        return $this->fetch();
    }


    //编辑详情
    public function edit()
    {
        $this->base_edit();//处理基础信息

        $ActivityLogInit  = new \init\ActivityLogInit();//报名记录  (ps:InitController)
        $ActivityLogModel = new \initmodel\ActivityLogModel(); //报名记录   (ps:InitModel)
        $params           = $this->request->param();

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "admin";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $ActivityLogInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        /** 数据格式转数组 **/
        $toArray = $result->toArray();
        foreach ($toArray as $k => $v) {
            $this->assign($k, $v);
        }

        return $this->fetch();
    }


    //提交编辑
    public function edit_post()
    {
        $ActivityLogInit  = new \init\ActivityLogInit();//报名记录   (ps:InitController)
        $ActivityLogModel = new \initmodel\ActivityLogModel(); //报名记录   (ps:InitModel)
        $params           = $this->request->param();


        if (($params["province_code"]) || ($params["city_code"]) || ($params["county_code"])) {
            //省市区基本信息
            $province_info = Db::name('region')->where('code', '=', $params['province_code'])->find();
            $city_info     = Db::name('region')->where('code', '=', $params['city_code'])->find();
            $county_info   = Db::name('region')->where('code', '=', $params['county_code'])->find();
            //name
            $params['province'] = $province_info['name'];
            $params['city']     = $city_info['name'];
            $params['county']   = $county_info['name'];
            //id
            $params['province_id'] = $province_info['id'];
            $params['city_id']     = $city_info['id'];
            $params['county_id']   = $county_info['id'];

            //地址
            $params['location'] = "{$province_info['name']}-{$city_info['name']}-{$county_info['name']}";

        }

        //情感状态: 其他
        if ($params['emotion'] == '其他' && $params['emotion_other']) $params['emotion'] = "其他({$params['emotion_other']})";

        //标签: 其他
        $tag = [];
        foreach ($params['tag'] as $key => $value) {
            if ($key == '其他') {
                $tag[] = "其他({$key})";
            } else {
                $tag[] = $key;
            }
        }
        $params['tag'] = $this->setParams($tag, '/');


        /** 增加一些判断 和前端判断校验一样 **/
        //验证身份证号是否正确
        if (!$this->validateIdCard($params["id_number"])) $this->error("身份证号格式错误!");


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


        /** 更改数据条件 && 或$params中存在id本字段可以忽略 **/
        $where = [];
        if ($params['id']) $where[] = ['id', '=', $params['id']];


        /** 提交数据 **/
        $result = $ActivityLogInit->admin_edit_post($params, $where);
        if (empty($result)) $this->error("失败请重试");

        $this->success("保存成功", "index{$this->params_url}");
    }


    //提交(副本,无任何操作) 编辑&添加
    public function edit_post_two()
    {
        $ActivityLogInit  = new \init\ActivityLogInit();//报名记录   (ps:InitController)
        $ActivityLogModel = new \initmodel\ActivityLogModel(); //报名记录   (ps:InitModel)
        $params           = $this->request->param();

        /** 更改数据条件 && 或$params中存在id本字段可以忽略 **/
        $where = [];
        if ($params['id']) $where[] = ['id', '=', $params['id']];

        /** 提交数据 **/
        $result = $ActivityLogInit->edit_post_two($params, $where);
        if (empty($result)) $this->error("失败请重试");

        $this->success("保存成功", "index{$this->params_url}");
    }


    //驳回
    public function refuse()
    {
        $ActivityLogInit  = new \init\ActivityLogInit();//报名记录  (ps:InitController)
        $ActivityLogModel = new \initmodel\ActivityLogModel(); //报名记录   (ps:InitModel)
        $params           = $this->request->param();

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "admin";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $ActivityLogInit->get_find($where, $params);
        if (empty($result)) $this->error("暂无数据");

        /** 数据格式转数组 **/
        $toArray = $result->toArray();
        foreach ($toArray as $k => $v) {
            $this->assign($k, $v);
        }

        return $this->fetch();
    }


    //驳回,更改状态
    public function audit_post()
    {
        $ActivityLogInit  = new \init\ActivityLogInit();//报名记录   (ps:InitController)
        $ActivityLogModel = new \initmodel\ActivityLogModel(); //报名记录   (ps:InitModel)
        $params           = $this->request->param();

        /** 更改数据条件 && 或$params中存在id本字段可以忽略 **/
        $where = [];
        if ($params['id']) $where[] = ['id', '=', $params['id']];


        /** 查询数据 **/
        $params["InterfaceType"] = "admin";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $item                    = $ActivityLogInit->get_find($where);
        if (empty($item)) $this->error("暂无数据");

        /** 通过&拒绝时间 **/
        if ($params['status'] == 2) $params['pass_time'] = time();
        if ($params['status'] == 3) $params['refuse_time'] = time();

        /** 提交数据 **/
        $result = $ActivityLogInit->edit_post_two($params, $where);
        if (empty($result)) $this->error("失败请重试");

        $this->success("操作成功");
    }

    //删除
    public function delete()
    {
        $ActivityLogInit  = new \init\ActivityLogInit();//报名记录   (ps:InitController)
        $ActivityLogModel = new \initmodel\ActivityLogModel(); //报名记录   (ps:InitModel)
        $params           = $this->request->param();

        if ($params["id"]) $id = $params["id"];
        if (empty($params["id"])) $id = $this->request->param("ids/a");

        /** 删除数据 **/
        $result = $ActivityLogInit->delete_post($id);
        if (empty($result)) $this->error("失败请重试");

        $this->success("删除成功");//   , "index{$this->params_url}"
    }


    //批量操作
    public function batch_post()
    {
        $ActivityLogInit  = new \init\ActivityLogInit();//报名记录   (ps:InitController)
        $ActivityLogModel = new \initmodel\ActivityLogModel(); //报名记录   (ps:InitModel)
        $params           = $this->request->param();

        $id = $this->request->param("id/a");
        if (empty($id)) $id = $this->request->param("ids/a");

        //提交编辑
        $result = $ActivityLogInit->batch_post($id, $params);
        if (empty($result)) $this->error("失败请重试");

        $this->success("保存成功");//   , "index{$this->params_url}"
    }


    //更新排序
    public function list_order_post()
    {
        $ActivityLogInit  = new \init\ActivityLogInit();//报名记录   (ps:InitController)
        $ActivityLogModel = new \initmodel\ActivityLogModel(); //报名记录   (ps:InitModel)
        $params           = $this->request->param("list_order/a");

        //提交更新
        $result = $ActivityLogInit->list_order_post($params);
        if (empty($result)) $this->error("失败请重试");

        $this->success("保存成功"); //   , "index{$this->params_url}"
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
