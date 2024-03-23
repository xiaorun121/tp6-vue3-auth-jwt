<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\api\model\Chat as ModelChat;
use app\BaseController;
use think\Request;

class Chat extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(ModelChat $chat)
    {
        $result = $chat::order('id desc')->select()->toArray();

        if($result){
            return result($result,'query chat success',200);
        }else{
            return result(null,'query chat error',0);
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
    public function save(Request $request, ModelChat $chat)
    {
        $data = $request->param();

        $result = $chat::create($data);

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
    public function read(ModelChat $chat,$id)
    {
        $result = $chat::find($id)->toArray();

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
    public function update(Request $request,ModelChat $chat, $id)
    {
        $data = $request->param('data');

        $chat = $chat::find($id);
        $chat->data($data,true);
        $result = $chat->save();

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
    public function delete(ModelChat $chat, $id)
    {
        $result = $chat::destroy($id);

        if($result){
            return result(null,'delete data success',200);
        }else{
            return result(null,'delete data exception',200);
        }
    }
}
