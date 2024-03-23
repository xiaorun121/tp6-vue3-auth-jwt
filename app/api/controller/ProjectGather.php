<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\api\model\AppTrace;
use app\BaseController;
use think\facade\Db;
use think\Request;

class ProjectGather extends BaseController
{ 
    public function index(Request $request){
        $time = $request->param('time','year');
        // 第一步查询 b_project 字段的 distinct 值
        $bProjectValues = Db::name('app_trace')->where('b_project <> "" ')->distinct(true)->column('b_project');

        // 第二步根据第一步结果查询 status 中已上架、已下架和审核中的数据条数
        // 1=>'已上架',2=>'审核中',3=>'待审核',4=>'账号禁用',5=>'分配中',6=>'已暂停',7=>'待验证',8=>'已下架',9=>'账号关联',10=>'更新中',11=>'其他'
        $result = Db::name('app_trace')
            ->field('b_project, 
                SUM(CASE WHEN status = "1" THEN 1 ELSE 0 END) AS yishangjia,
                SUM(CASE WHEN status = "2" THEN 1 ELSE 0 END) AS shenhezhong,
                SUM(CASE WHEN status = "3" THEN 1 ELSE 0 END) AS daishenhe,
                SUM(CASE WHEN status = "4" THEN 1 ELSE 0 END) AS zhanghaojinyong,
                SUM(CASE WHEN status = "5" THEN 1 ELSE 0 END) AS fenpeizhong,
                SUM(CASE WHEN status = "6" THEN 1 ELSE 0 END) AS yizanting,
                SUM(CASE WHEN status = "7" THEN 1 ELSE 0 END) AS daiyanzheng,
                SUM(CASE WHEN status = "8" THEN 1 ELSE 0 END) AS yixiajia,
                SUM(CASE WHEN status = "9" THEN 1 ELSE 0 END) AS zhanghaoguanlian,
                SUM(CASE WHEN status = "10" THEN 1 ELSE 0 END) AS gengxinzhong,
                SUM(CASE WHEN status = "11" THEN 1 ELSE 0 END) AS qita
            ')
            ->whereIn('b_project', $bProjectValues)
            ->whereTime('update_time', $time)
            ->group('b_project')
            ->select()->toArray();

        if($result){
            return result($result,'query data success',200);
        }else{
            return result(null,'query data error',0);
        }
    }

    // 显示项目组数据列表数据
    public function showProject(AppTrace $appTrace, Request $request){
        $data = $request->param();

        $result = $appTrace->where($data)->select()->toArray();

        if($result){
            return result($result,'query data success',200);
        }else{
            return result(null,'query data error',0);
        }
    }
}
