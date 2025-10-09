<?php

namespace app\admin\controller;


/**
 * @adminMenuRoot(
 *     "name"                =>"Statistics",
 *     "name_underline"      =>"statistics",
 *     "controller_name"     =>"Statistics",
 *     "table_name"          =>"statistics",
 *     "action"              =>"default",
 *     "parent"              =>"",
 *     "display"             => true,
 *     "order"               => 10000,
 *     "icon"                =>"none",
 *     "remark"              =>"统计管理",
 *     "author"              =>"",
 *     "create_time"         =>"2025-10-09 10:20:26",
 *     "version"             =>"1.0",
 *     "use"                 => new \app\admin\controller\StatisticsController();
 * )
 */


use think\facade\Db;
use cmf\controller\AdminBaseController;


class StatisticsController extends AdminBaseController
{


    /**
     * 首页列表数据
     * @adminMenu(
     *     'name'             => 'Statistics',
     *     'name_underline'   => 'statistics',
     *     'parent'           => 'index',
     *     'display'          => true,
     *     'hasView'          => true,
     *     'order'            => 10000,
     *     'icon'             => '',
     *     'remark'           => '统计管理',
     *     'param'            => ''
     * )
     */
    public function index()
    {
        $MemberModel       = new \initmodel\MemberModel();//用户管理
        $ActivityModel     = new \initmodel\ActivityModel(); //活动管理   (ps:InitModel)
        $ActivityLogModel  = new \initmodel\ActivityLogModel(); //报名记录   (ps:InitModel)
        $ActivityVoteModel = new \initmodel\ActivityVoteModel(); //投票记录   (ps:InitModel)

        //数量统计
        $member_count        = $MemberModel->count();
        $activity_count      = $ActivityModel->count();
        $activity_log_count  = $ActivityLogModel->count();
        $activity_vote_count = $ActivityVoteModel->count();
        $this->assign("activity_vote_count", $activity_vote_count);
        $this->assign("activity_count", $activity_count);
        $this->assign("activity_log_count", $activity_log_count);
        $this->assign("member_count", $member_count);

        //饼状图统计
        /** 计算男女比例 **/
        // 获取男性用户数量
        $maleCount = $ActivityLogModel->where('gender', '男')->count();
        // 获取女性用户数量
        $femaleCount = $ActivityLogModel->where('gender', '女')->count();
        // 计算总人数
        $totalCount = $activity_log_count;
        // 计算男女比例
        if ($totalCount > 0) {
            $maleRatio   = $maleCount / $totalCount * 100;
            $femaleRatio = $femaleCount / $totalCount * 100;
        } else {
            // 如果没有数据，默认比例为0
            $maleRatio   = 0;
            $femaleRatio = 0;
        }
        $proportion_data = json_encode([
            ['value' => round($maleRatio, 2), 'name' => '男'],
            ['value' => round($femaleRatio, 2), 'name' => '女'],
        ]);
        $this->assign('proportion_data', $proportion_data);

        /** 年龄占比 **/
        //18-30
        $age_20_30_count = $ActivityLogModel->whereBetween('age', [18, 30])->count();
        //30-40
        $age_30_40_count = $ActivityLogModel->whereBetween('age', [30, 40])->count();
        //40-50
        $age_40_50_count = $ActivityLogModel->whereBetween('age', [40, 50])->count();

        //计算总人数
        $total_age_count = $activity_log_count;
        //计算比例
        if ($total_age_count > 0) {
            $age_20_30_ratio = $age_20_30_count / $total_age_count * 100;
            $age_30_40_ratio = $age_30_40_count / $total_age_count * 100;
            $age_40_50_ratio = $age_40_50_count / $total_age_count * 100;
        } else {
            $age_20_30_ratio = 0;
            $age_30_40_ratio = 0;
            $age_40_50_ratio = 0;
        }
        $age_data = json_encode([
            ['value' => round($age_20_30_ratio, 2), 'name' => '18-30(岁)'],
            ['value' => round($age_30_40_ratio, 2), 'name' => '30-40(岁)'],
            ['value' => round($age_40_50_ratio, 2), 'name' => '40-50(岁)'],
        ]);
        $this->assign('age_data', $age_data);

        /**用户注册柱状图**/
        // 初始化日期范围数组
        $startDate  = strtotime('-1 month');
        $endDate    = strtotime('now');
        $day_list   = [];
        $count_list = [];

        // 初始化每日注册量 - 用户注册
        $tempDate = $startDate;
        while ($tempDate <= $endDate) {
            $date         = date('Y-m-d', $tempDate);
            $tempDate     = strtotime('+1 day', $tempDate);
            $day_list[]   = $date; //日期
            $count_list[] = $MemberModel->where('create_time', 'between', [strtotime($date . ' 00:00:00'), strtotime($date . ' 23:59:59')])->count();
        }
        $xAxis_data  = json_encode([
            'type'     => 'category',
            'data'     => $day_list,
            'axisTick' => ['alignWithLabel' => true],
        ]);
        $series_data = json_encode([
            'name'     => '用户注册增长',
            'type'     => 'bar',
            'barWidth' => '60%',
            'data'     => $count_list,
        ]);

        $this->assign('xAxis_data', $xAxis_data);
        $this->assign('series_data', $series_data);

        /**报名人数柱状图**/
        $day_list2   = [];
        $count2_list = [];

        // 重新初始化日期范围
        $startDate2  = strtotime('-1 month');
        $endDate2    = strtotime('now');

        // 初始化每日报名量
        $tempDate2 = $startDate2;
        while ($tempDate2 <= $endDate2) {
            $date2         = date('Y-m-d', $tempDate2);
            $tempDate2     = strtotime('+1 day', $tempDate2);
            $day_list2[]   = $date2; //日期
            $count2_list[] = $ActivityLogModel->where('create_time', 'between', [strtotime($date2 . ' 00:00:00'), strtotime($date2 . ' 23:59:59')])->count();
        }
        $xAxis2_data = json_encode([
            'type'     => 'category',
            'data'     => $day_list2,
            'axisTick' => ['alignWithLabel' => true],
        ]);
        $series2_data = json_encode([
            'name'     => '报名数',
            'type'     => 'bar',
            'barWidth' => '60%',
            'data'     => $count2_list,
        ]);
        $this->assign('xAxis2_data', $xAxis2_data);
        $this->assign('series2_data', $series2_data);

        /**投票数柱状图**/
        $day_list3   = [];
        $count3_list = [];

        // 重新初始化日期范围
        $startDate3  = strtotime('-1 month');
        $endDate3    = strtotime('now');

        // 初始化每日投票量
        $tempDate3 = $startDate3;
        while ($tempDate3 <= $endDate3) {
            $date3         = date('Y-m-d', $tempDate3);
            $tempDate3     = strtotime('+1 day', $tempDate3);
            $day_list3[]   = $date3; //日期
            $count3_list[] = $ActivityVoteModel->where('create_time', 'between', [strtotime($date3 . ' 00:00:00'), strtotime($date3 . ' 23:59:59')])->count();
        }
        $xAxis3_data = json_encode([
            'type'     => 'category',
            'data'     => $day_list3,
            'axisTick' => ['alignWithLabel' => true],
        ]);
        $series3_data = json_encode([
            'name'     => '投票数',
            'type'     => 'bar',
            'barWidth' => '60%',
            'data'     => $count3_list,
        ]);
        $this->assign('xAxis3_data', $xAxis3_data);
        $this->assign('series3_data', $series3_data);

        return $this->fetch();
    }

}
