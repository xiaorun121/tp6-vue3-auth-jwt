<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\api\model\Logs;
use app\BaseController;
use think\Request;

class LiveUpdateLogs extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Logs $logs, Request $request)
    {
        $data = $request->param();

        $page = $data['page'];
        $pageSize = $data['pageSize'];
        $where['status'] = 2;

        $result = $logs->where($where)->order('id desc')->paginate($pageSize,false,[
            'page' => $page
        ]);

        $resultInfo = $result->toArray();

        if($resultInfo){

            if(empty($resultInfo['data'])){
                return result(['page'=>$page],'query data not found',210);
            }
            
            return result($resultInfo,'query data success',200);
        }else{
            return result(null,'query data error',0);
        }
    }

}
