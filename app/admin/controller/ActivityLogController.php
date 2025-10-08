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

    // public function initialize(){
    //	//报名记录
    //	parent::initialize();
    //	}


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
        $params           = $this->request->param();


        /** 检测参数信息 **/
        $validateResult = $this->validate($params, 'ActivityLog');
        if ($validateResult !== true) $this->error($validateResult);


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


        /** 检测参数信息 **/
        $validateResult = $this->validate($params, 'ActivityLog');
        if ($validateResult !== true) $this->error($validateResult);


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


}
