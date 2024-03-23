<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\api\model\Logs as ModelLogs;
use app\BaseController;
use think\Request;

class Logs extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(ModelLogs $logs, Request $request)
    {
        $data = $request->param();

        $page = $data['page'];
        $pageSize = $data['pageSize'];
        $where = [];

        if(!empty($data['user_id'])){
            $where['user_id'] = $data['user_id'];
        }

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

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
