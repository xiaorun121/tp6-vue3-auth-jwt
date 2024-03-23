<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\api\model\AuthGroup as ModelAuthGroup;
use app\api\model\Menu;
use think\Request;
use app\BaseController;
use think\Facade\Db;

class AuthGroup extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(ModelAuthGroup $authGroup)
    {
        // $result = $authGroup::queryData();  

        $result = Db::view(['authgroup'=>'authgroup'])->select()->toArray();
        foreach($result as $key => $value){
            $result[$key]['status'] = $value['status'] == 1 ? true : false;
        }

        $menu_data = get_tree_three(Menu::authGroup());

        if($result){
            $data = [
                'data'  => $result,
                'menu'  => $menu_data,
            ];
            return result($data,'authGroup query success',200);
        }else{
            return result(null,'authGroup exception',0);
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
     * array_values 取出array里面value的值
     * implode 以逗号隔开
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(ModelAuthGroup $authGroup, Request $request)
    {
        $data = $request->param();

        $data['rules'] = implode(',',array_values($data['rules']));

        $result = $authGroup::create($data);

        if($result){
            return result(null,'data save success',200);
        }else{
            return result(null,'data save exception',0);
        }
        
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read(ModelAuthGroup $authGroup,$id)
    {
        $result = $authGroup->find($id)->toArray();

        $menu_data = get_tree_three_isChecked(Menu::authGroup(),0,explode(',',$result['rules']));

        if($result && $menu_data){
            $data = [
                'data'  => $result,
                'menu'  => $menu_data,
            ];
            return result($data,'authGroup query success',200);
        }else{
            return result(null,'authGroup query exception',0);
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
    public function update(ModelAuthGroup $authGroup, Request $request, $id)
    {
        $data = $request->param('data');

        if(isset($data['rules'])){
            $data['rules'] = implode(',',array_values($data['rules']));
        }

        
        $authGroup = $authGroup::find($id);
        $authGroup->data($data,true);

        $result = $authGroup->save();

        if($result){
            return result(null,'save data success',200);
        }else{
            return result(null,'save data exception',200);
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete(ModelAuthGroup $authGroup,$id)
    {
        $result = $authGroup::destroy($id);

        if($result){
            return result(null,'delete data success',200);
        }else{
            return result(null,'delete data exception',200);
        }
    }
}
