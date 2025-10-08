<?php

namespace app\admin\controller;


/**
 * @adminMenuRoot(
 *     "name"                =>"ActivityRemark",
 *     "name_underline"      =>"activity_remark",
 *     "controller_name"     =>"ActivityRemark",
 *     "table_name"          =>"activity_remark",
 *     "action"              =>"default",
 *     "parent"              =>"",
 *     "display"             => true,
 *     "order"               => 10000,
 *     "icon"                =>"none",
 *     "remark"              =>"备注管理",
 *     "author"              =>"",
 *     "create_time"         =>"2025-10-08 16:53:34",
 *     "version"             =>"1.0",
 *     "use"                 => new \app\admin\controller\ActivityRemarkController();
 * )
 */


use think\facade\Db;
use cmf\controller\AdminBaseController;


class ActivityRemarkController extends AdminBaseController
{

    // public function initialize(){
    //	//备注管理
    //	parent::initialize();
    //	}


    /**
     * 首页基础信息
     */
    protected function base_index()
    {

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
     *     'name'             => 'ActivityRemark',
     *     'name_underline'   => 'activity_remark',
     *     'parent'           => 'index',
     *     'display'          => true,
     *     'hasView'          => true,
     *     'order'            => 10000,
     *     'icon'             => '',
     *     'remark'           => '备注管理',
     *     'param'            => ''
     * )
     */
    public function index()
    {
        $this->base_index();//处理基础信息


        $ActivityRemarkInit  = new \init\ActivityRemarkInit();//备注管理    (ps:InitController)
        $ActivityRemarkModel = new \initmodel\ActivityRemarkModel(); //备注管理   (ps:InitModel)
        $params              = $this->request->param();

        /** 查询条件 **/
        $where = [];
        //$where[]=["type","=", 1];
        if ($params["keyword"]) $where[] = ["remark", "like", "%{$params["keyword"]}%"];
        if ($params["log_id"]) $where[] = ["log_id", "=", $params["log_id"]];
        if ($params["test"]) $where[] = ["test", "=", $params["test"]];


        //$where[] = $this->getBetweenTime($params['begin_time'], $params['end_time']);
        //if($params["status"]) $where[]=["status","=", $params["status"]];
        //$where[]=["type","=", 1];


        /** 查询数据 **/
        $params["InterfaceType"] = "admin";//接口类型
        $params["DataFormat"]    = "list";//数据格式,find详情,list列表
        $params["field"]         = "*";//过滤字段


        /** 导出数据 **/
        if ($params["is_export"]) $ActivityRemarkInit->export_excel($where, $params);


        /** 查询数据 **/
        $result = $ActivityRemarkInit->get_list_paginate($where, $params);


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
        $ActivityRemarkInit  = new \init\ActivityRemarkInit();//备注管理   (ps:InitController)
        $ActivityRemarkModel = new \initmodel\ActivityRemarkModel(); //备注管理   (ps:InitModel)
        $params              = $this->request->param();


        /** 检测参数信息 **/
        $validateResult = $this->validate($params, 'ActivityRemark');
        if ($validateResult !== true) $this->error($validateResult);


        /** 插入数据 **/
        $result = $ActivityRemarkInit->admin_edit_post($params);
        if (empty($result)) $this->error("失败请重试");

        $this->success("保存成功", "index{$this->params_url}");
    }


    //查看详情
    public function find()
    {
        $this->base_edit();//处理基础信息

        $ActivityRemarkInit  = new \init\ActivityRemarkInit();//备注管理    (ps:InitController)
        $ActivityRemarkModel = new \initmodel\ActivityRemarkModel(); //备注管理   (ps:InitModel)
        $params              = $this->request->param();

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "admin";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $ActivityRemarkInit->get_find($where, $params);
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

        $ActivityRemarkInit  = new \init\ActivityRemarkInit();//备注管理  (ps:InitController)
        $ActivityRemarkModel = new \initmodel\ActivityRemarkModel(); //备注管理   (ps:InitModel)
        $params              = $this->request->param();

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];

        /** 查询数据 **/
        $params["InterfaceType"] = "admin";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $ActivityRemarkInit->get_find($where, $params);
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
        $ActivityRemarkInit  = new \init\ActivityRemarkInit();//备注管理   (ps:InitController)
        $ActivityRemarkModel = new \initmodel\ActivityRemarkModel(); //备注管理   (ps:InitModel)
        $params              = $this->request->param();


        /** 检测参数信息 **/
        $validateResult = $this->validate($params, 'ActivityRemark');
        if ($validateResult !== true) $this->error($validateResult);


        /** 更改数据条件 && 或$params中存在id本字段可以忽略 **/
        $where = [];
        if ($params['id']) $where[] = ['id', '=', $params['id']];


        /** 提交数据 **/
        $result = $ActivityRemarkInit->admin_edit_post($params, $where);
        if (empty($result)) $this->error("失败请重试");

        $this->success("保存成功", "index{$this->params_url}");
    }


    //提交(副本,无任何操作) 编辑&添加
    public function edit_post_two()
    {
        $ActivityRemarkInit  = new \init\ActivityRemarkInit();//备注管理   (ps:InitController)
        $ActivityRemarkModel = new \initmodel\ActivityRemarkModel(); //备注管理   (ps:InitModel)
        $params              = $this->request->param();

        /** 更改数据条件 && 或$params中存在id本字段可以忽略 **/
        $where = [];
        if ($params['id']) $where[] = ['id', '=', $params['id']];

        /** 提交数据 **/
        $result = $ActivityRemarkInit->edit_post_two($params, $where);
        if (empty($result)) $this->error("失败请重试");

        $this->success("保存成功", "index{$this->params_url}");
    }


    //驳回
    public function refuse()
    {
        $ActivityRemarkInit  = new \init\ActivityRemarkInit();//备注管理  (ps:InitController)
        $ActivityRemarkModel = new \initmodel\ActivityRemarkModel(); //备注管理   (ps:InitModel)
        $params              = $this->request->param();

        /** 查询条件 **/
        $where   = [];
        $where[] = ["id", "=", $params["id"]];


        /** 查询数据 **/
        $params["InterfaceType"] = "admin";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $result                  = $ActivityRemarkInit->get_find($where, $params);
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
        $ActivityRemarkInit  = new \init\ActivityRemarkInit();//备注管理   (ps:InitController)
        $ActivityRemarkModel = new \initmodel\ActivityRemarkModel(); //备注管理   (ps:InitModel)
        $params              = $this->request->param();

        /** 更改数据条件 && 或$params中存在id本字段可以忽略 **/
        $where = [];
        if ($params['id']) $where[] = ['id', '=', $params['id']];


        /** 查询数据 **/
        $params["InterfaceType"] = "admin";//接口类型
        $params["DataFormat"]    = "find";//数据格式,find详情,list列表
        $item                    = $ActivityRemarkInit->get_find($where);
        if (empty($item)) $this->error("暂无数据");

        /** 通过&拒绝时间 **/
        if ($params['status'] == 2) $params['pass_time'] = time();
        if ($params['status'] == 3) $params['refuse_time'] = time();

        /** 提交数据 **/
        $result = $ActivityRemarkInit->edit_post_two($params, $where);
        if (empty($result)) $this->error("失败请重试");

        $this->success("操作成功");
    }

    //删除
    public function delete()
    {
        $ActivityRemarkInit  = new \init\ActivityRemarkInit();//备注管理   (ps:InitController)
        $ActivityRemarkModel = new \initmodel\ActivityRemarkModel(); //备注管理   (ps:InitModel)
        $params              = $this->request->param();

        if ($params["id"]) $id = $params["id"];
        if (empty($params["id"])) $id = $this->request->param("ids/a");

        /** 删除数据 **/
        $result = $ActivityRemarkInit->delete_post($id);
        if (empty($result)) $this->error("失败请重试");

        $this->success("删除成功");//   , "index{$this->params_url}"
    }


    //批量操作
    public function batch_post()
    {
        $ActivityRemarkInit  = new \init\ActivityRemarkInit();//备注管理   (ps:InitController)
        $ActivityRemarkModel = new \initmodel\ActivityRemarkModel(); //备注管理   (ps:InitModel)
        $params              = $this->request->param();

        $id = $this->request->param("id/a");
        if (empty($id)) $id = $this->request->param("ids/a");

        //提交编辑
        $result = $ActivityRemarkInit->batch_post($id, $params);
        if (empty($result)) $this->error("失败请重试");

        $this->success("保存成功");//   , "index{$this->params_url}"
    }


    //更新排序
    public function list_order_post()
    {
        $ActivityRemarkInit  = new \init\ActivityRemarkInit();//备注管理   (ps:InitController)
        $ActivityRemarkModel = new \initmodel\ActivityRemarkModel(); //备注管理   (ps:InitModel)
        $params              = $this->request->param("list_order/a");

        //提交更新
        $result = $ActivityRemarkInit->list_order_post($params);
        if (empty($result)) $this->error("失败请重试");

        $this->success("保存成功"); //   , "index{$this->params_url}"
    }


}
