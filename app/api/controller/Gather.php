<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\api\model\Gather as ModelGather;
use app\BaseController;
use think\Request;
use think\facade\Request as RequestFacade;

class Gather extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(ModelGather $gather, Request $request)
    {   
        $data = $request->param();
        $page = $data['page'];
        $pageSize = $data['pageSize'];
        
        $result = $gather::order('id desc')->paginate($pageSize,false,[
            'page' => $page
        ]);

        $resultInfo = $result->toArray();

        if($resultInfo){
            return result($resultInfo,'query gather success',200);
        }else{
            return result(null,'query gather error',0);
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
    public function save(Request $request, ModelGather $gather)
    {
        $data = $request->param();

        $result = $gather::create($data);

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
    public function read(ModelGather $gather,$id)
    {
        $result = $gather::find($id)->toArray();

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
    public function update(Request $request,ModelGather $gather, $id)
    {
        $data = $request->param('data');

        $gather = $gather::find($id);
        $gather->data($data,true);
        $result = $gather->save();

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
    public function delete(ModelGather $gather, $id)
    {
        $result = $gather::destroy($id);

        if($result){
            return result(null,'delete data success',200);
        }else{
            return result(null,'delete data exception',200);
        }
    }

    /**
     * 上传编辑器图片 
     */ 
    public function uploadEditorImage(Request $request){

        $file = $request->file('file');

        // 获取文件名
        // $fileName = $file->getOriginalName();

        $savename = \think\facade\Filesystem::disk('public')->putFile( 'topic', $file);

        if($savename){
            $savename = str_replace('\\','/',$savename);

            $imgUrl = RequestFacade::domain()."/storage/".$savename;

            return result($imgUrl,'上传图片成功',200); 
        }else{
            return result(null,'上传图片失败',0);
        }
    }
}
