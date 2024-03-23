<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\api\exception\TableNotExistsException;
use app\api\model\Backend as ModelBackend;
use app\BaseController;
use think\Request;

class Backend extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(ModelBackend $backend, Request $request)
    {   
        $data = $request->param();
        $page = $data['page'];
        $pageSize = $data['pageSize'];
        
        $result = $backend::order('id desc')->paginate($pageSize,false,[
            'page' => $page
        ]);

        $resultInfo = $result->toArray();

        if($resultInfo){
            return result($resultInfo,'query backend success',200);
        }else{
            return result(null,'query backend error',0);
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
    public function save(Request $request, ModelBackend $backend)
    {
        $data = $request->param();

        $result = $backend::create($data);

        if($result){
            return result($data,'save data success',200);
        }else{
            return result(null,'save data error',0);
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read(ModelBackend $backend,$id)
    {
        $result = $backend::find($id)->toArray();

        if($result){
            return result($result,'for Id query data success',200);
        }else{
            return result($result,'for Id query data error',0);
        }
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
    public function update(Request $request,ModelBackend $backend, $id)
    {
        $data = $request->param('data');

        $backend = $backend::find($id);
        $backend->data($data,true);
        $result = $backend->save();

        if($result){
            return result($data,'save data success',200);
        }else{
            return result(null,'save data error',0);
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete(ModelBackend $backend, $id)
    {
        $result = $backend::destroy($id);

        if($result){
            return result(null,'delete data success',200);
        }else{
            return result(null,'delete data exception',200);
        }
    }
}
