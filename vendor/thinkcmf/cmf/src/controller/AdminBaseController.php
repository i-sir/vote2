<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-present http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +---------------------------------------------------------------------
// | Author: 小夏 < 449134904@qq.com>
// +----------------------------------------------------------------------
namespace cmf\controller;

use app\admin\model\UserModel;
use think\facade\Db;
use think\facade\Request;

error_reporting(0);


class AdminBaseController extends BaseController
{
    public $admin_info = null;
    public $params_url = null;

    protected function initialize()
    {
        // 监听admin_init
        hook('admin_init');
        parent::initialize();
        $sessionAdminId = session('ADMIN_ID');
        if (!empty($sessionAdminId)) {
            $user             = UserModel::where('id', $sessionAdminId)->find();
            $this->admin_info = $user;

            if (!$this->checkAccess($sessionAdminId)) {
                $this->error("您没有访问权限！");
            }
            $this->assign("admin", $user);
        } else {
            if ($this->request->isPost()) {
                $this->error("您还没有登录！", url("admin/Public/login"));
            } else {
                return $this->redirect(url("admin/Public/login"));
            }
        }

        //处理 get参数
        $get_data = $this->request->get();
        foreach ($get_data as $k => $v) {
            $this->assign($k, $v);
        }
        $this->params_url = "?" . http_build_query($get_data);
    }

    public function _initializeView()
    {
        $this->updateViewConfig();
    }


    /**
     * 获取用户信息
     * @param $user_id 用户id
     * @return mixed
     */
    public function get_user_info($user_id)
    {
        $MemberModel = new \initmodel\MemberModel();//用户管理

        $item = $MemberModel->where('id', '=', $user_id)->find();
        if ($item) $item['avatar'] = cmf_get_asset_url($item['avatar']);
        return $item;
    }


    /**
     * 获取管理员信息
     * @param $user_id 用户id
     * @return mixed
     */
    public function get_admin_info($user_id)
    {
        $AdminUserInit = new \init\AdminUserInit();//管理员    (ps:InitController)
        $item          = $AdminUserInit->get_find($user_id);
        return $item;
    }


    /**
     * 获取时间区间值
     * @param $begin      开始时间 2022-11-1 15:53:55
     * @param $end        结束时间 2022-11-1 15:53:55
     * @param $Field      筛选时间字段名
     * @return array   [$beginField, 'between', [$beginTime, $endTime]];
     */
    protected function getBetweenTime($begin = '', $end = '', $Field = 'create_time')
    {
        $where[] = [$Field, 'between', [0, 999999999999]];

        if (!empty($begin)) {
            unset($where);
            $beginTime = strtotime($begin);//默认 00:00:00
            $where[]   = [$Field, 'between', [$beginTime, 999999999999]];
        }

        if (!empty($end)) {
            unset($where);
            $strlen = strlen($end);
            if ($strlen > 10) $endTime = strtotime($end);//传入 年月日,时分秒不用转换
            if ($strlen <= 10) $endTime = strtotime($end . '23:59:59');//传入 年月日,年月  拼接时分秒
            $where[] = [$Field, 'between', [0, $endTime]];
        }

        if (!empty($begin) && !empty($end)) {
            unset($where);
            $beginTime = strtotime($begin);
            $strlen    = strlen($end);
            if ($strlen > 10) $endTime = strtotime($end);
            if ($strlen <= 10) $endTime = strtotime($end . '23:59:59');//传入 年月日,年月  拼接时分秒
            $where = [$Field, 'between', [$beginTime, $endTime]];
        }
        return $where;
    }


 
    private function updateViewConfig($defaultTheme = '', $viewBase = '')
    {
        $cmfAdminThemePath = config('template.cmf_admin_theme_path');

        if (empty($defaultTheme)) {
            $cmfAdminDefaultTheme = cmf_get_current_admin_theme();
        } else {
            $cmfAdminDefaultTheme = $defaultTheme;
        }

        $themePath = "{$cmfAdminThemePath}{$cmfAdminDefaultTheme}";

        $root = cmf_get_root();

        //使cdn设置生效
        $cdnSettings = cmf_get_option('cdn_settings');
        if (empty($cdnSettings['cdn_static_root'])) {
            $viewReplaceStr = [
                '__ROOT__'     => $root,
                '__TMPL__'     => "{$root}/{$themePath}",
                '__STATIC__'   => "{$root}/static",
                '__WEB_ROOT__' => $root
            ];
        } else {
            $cdnStaticRoot  = rtrim($cdnSettings['cdn_static_root'], '/');
            $viewReplaceStr = [
                '__ROOT__'     => $root,
                '__TMPL__'     => "{$cdnStaticRoot}/{$themePath}",
                '__STATIC__'   => "{$cdnStaticRoot}/static",
                '__WEB_ROOT__' => $cdnStaticRoot
            ];
        }

        if (empty($viewBase)) {
            $viewBase = WEB_ROOT . $themePath . '/';
        }

        $this->view->engine()->config([
            'view_base'          => $viewBase,
            'tpl_replace_string' => $viewReplaceStr
        ]);
    }

    /**
     * 加载模板输出
     * @access protected
     * @param string $template 模板文件名
     * @param array  $vars     模板输出变量
     * @param array  $config   模板参数
     * @return mixed
     */
    protected function fetch($template = '', $vars = [], $config = [])
    {
        $template = $this->parseTemplate($template);
        $content  = $this->view->fetch($template, $vars, $config);

        return $content;
    }

    /**
     * 自动定位模板文件
     * @access private
     * @param string $template 模板文件规则
     * @return string
     */
    protected function parseTemplate($template)
    {
        // 分析模板文件规则
        $request = $this->request;
        // 获取视图根目录
        if (strpos($template, '@')) {
            // 跨模块调用
            list($app, $template) = explode('@', $template);
        }

        $cmfAdminThemePath    = config('template.cmf_admin_theme_path');
        $cmfAdminDefaultTheme = cmf_get_current_admin_theme();
        $themePath            = "{$cmfAdminThemePath}{$cmfAdminDefaultTheme}/";

        // 基础视图目录
        $app = isset($app) ? $app : $this->app->http->getName();
        //        $path = $themePath . ($app ? $app . DIRECTORY_SEPARATOR : '');

        $depr = config('view.view_depr');
        if (0 !== strpos($template, '/')) {
            $template   = str_replace(['/', ':'], $depr, $template);
            $controller = cmf_parse_name($request->controller());
            if ($controller) {
                if ('' == $template) {
                    // 如果模板文件名为空 按照默认规则定位
                    $template = str_replace('.', DIRECTORY_SEPARATOR, $controller) . $depr . cmf_parse_name($request->action(false));
                } elseif (false === strpos($template, $depr)) {
                    $template = str_replace('.', DIRECTORY_SEPARATOR, $controller) . $depr . $template;
                }
            }
        } else {
            $template = str_replace(['/', ':'], $depr, substr($template, 1));
        }

        $file = $themePath . ($app ? $app . DIRECTORY_SEPARATOR : '') . ltrim($template, '/') . '.' . ltrim(config('view.view_suffix'), '.');

        if (!is_file($file)) {

            $adminDefaultTheme = 'admin_simpleboot3';

            $cmfAdminThemePath = config('template.cmf_admin_theme_path');
            $themePath         = "{$cmfAdminThemePath}{$adminDefaultTheme}";
            $viewBase          = WEB_ROOT . $themePath . '/';

            $defaultFile = $viewBase . ($app ? $app . DIRECTORY_SEPARATOR : '') . ltrim($template, '/') . '.' . ltrim(config('view.view_suffix'), '.');

            if (is_file($defaultFile)) {
                $file = $defaultFile;
                $this->updateViewConfig($adminDefaultTheme);
            }
        }

        return $file;
    }

    /**
     * 初始化后台菜单
     */
    public function initMenu()
    {
    }

    /**
     *  检查后台用户访问权限
     * @param int $userId 后台用户id
     * @return boolean 检查通过返回true
     */
    private function checkAccess($userId)
    {
        // 如果用户id是1，则无需判断
        if ($userId == 1) {
            return true;
        }

        $app        = $this->app->http->getName();
        $controller = $this->request->controller();
        $action     = $this->request->action();
        $rule       = $app . $controller . $action;

        $notRequire = ["adminIndexindex", "adminMainindex"];
        if (!in_array($rule, $notRequire)) {
            return cmf_auth_check($userId);
        } else {
            return true;
        }
    }




    /**
     * 获取唯一单号或唯一编码
     * @param string $field_name 字段名
     * @param int    $length     长度(订单号类型，默认8位)
     * @param int    $type       生成类型：1:数子 2:数字+字母 3:纯数字 4:纯字母(大小写) 5:纯字母(大写)
     * @param string $prefix     前缀
     * @param string $table_name 表名|如为空,控制器必须和model名一致
     * @return string 唯一编号
     */
    protected function get_num_only($field_name = 'order_num', $length = 4, $type = 1, $prefix = '', $table_name = '')
    {
        static $attempts = 0;
        $attempts++;

        // 生成基础编号
        if ($type == 1) $only_num = $prefix . cmf_order_sn($length);
        if ($type == 2) $only_num = $prefix . cmf_random_string($length);
        if ($type == 3) $only_num = $prefix . $this->generatePureNumber($length);
        if ($type == 4) $only_num = $prefix . $this->generatePureLetters($length);
        if ($type == 5) $only_num = $prefix . $this->generateUppercasePureLetters($length);

        // 如果超过50次尝试，追加3位随机数字
        if ($attempts > 50) {
            $only_num .= mt_rand(100, 999); // 直接生成100-999的随机数
            $attempts = 0;
        }

        // 检查唯一性
        if ($table_name && is_string($table_name)) {
            $is = Db::name($table_name)->where($field_name, '=', $only_num)->count();
        } elseif (is_object($table_name)) {
            $is = $table_name->where($field_name, '=', $only_num)->count();
        } else {
            $model_class = "\\initmodel\\" . Request::controller() . "Model";
            $Model       = new $model_class();
            $is          = $Model->where($field_name, '=', $only_num)->count();
        }

        if ($is) {
            return $this->get_num_only($field_name, $length, $type, $prefix, $table_name);
        }

        $attempts = 0;
        return $only_num;
    }


    /**
     * 生成纯数字
     * @param $length
     * @return string
     */
    protected function generatePureNumber($length)
    {
        $digits = '0123456789';
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $digits[rand(0, strlen($digits) - 1)];
        }
        return $result;
    }

    /**
     * 生成纯字母
     * @param $length
     * @return string
     */
    protected function generatePureLetters($length)
    {
        $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $result  = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $letters[rand(0, strlen($letters) - 1)];
        }
        return $result;
    }


    /**
     * 生成纯字母 大写
     * @param $length
     * @return string
     */
    protected function generateUppercasePureLetters($length)
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $result  = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $letters[rand(0, strlen($letters) - 1)];
        }
        return $result;
    }


    /**
     * 插入随机下划线
     * @param $inputString 字符串
     * @return array|string|string[]
     */
    protected function insertRandomUnderscore($inputString)
    {
        // 获取字符串长度
        $length = strlen($inputString);

        // 如果字符串长度小于等于 1，直接返回原字符串
        if ($length <= 1) return $inputString;

        // 生成一个随机位置，范围从 1 到 $length - 1，确保不在首尾
        $randomPosition = mt_rand(1, $length - 1);

        // 在随机位置插入下划线
        $resultString = substr_replace($inputString, '_', $randomPosition, 0);

        return $resultString;
    }






}
