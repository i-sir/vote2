<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------

namespace plugins\form\controller;

//Demo插件英文名，改成你的插件英文就行了
use cmf\controller\PluginBaseController;
use think\Db;

class IndexController extends PluginBaseController
{
    public function index()
    {

        return json_encode(['ss'=>"ss"]);
    }
}
