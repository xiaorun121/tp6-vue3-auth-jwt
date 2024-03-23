<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\api\model\Menu as ModelMenu;
use app\BaseController;
use think\Request;
use think\facade\Cache;
use app\api\model\Admin;


class Menu extends BaseController
{
    /**
     * 显示资源列表
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function index(Request $request,ModelMenu $menu)
    {      
        // 获取当前登录的用户id
        $param = $request->param();

        $is_menu = $request->param('is_menu');

        

        // 查询菜单栏目里面的数据 是不包含user_id
        if(isset($param['user_id'])){

            $user_id = $param['user_id'];

            $admin = Admin::find($user_id)->toArray();

            // 判断是否是超级管理员  显示所有权限
            if($admin['is_admin'] == true){
                
                $menuList = $menu::queryData($is_menu);      

                $result = get_tree($menuList);

                foreach($result as $key => $value){
                    // unset($result[$key]['parent_id']);
                    unset($result[$key]['level']);
                }

                $result_left = get_tree_left($menu::queryData(1,true, $user_id));

                if($result && $result_left){
                    $data = [
                        'data' => $result,
                        'data_left' => $result_left,
                    ];
                    return result($data, 'Menu query success', 200);
                }else{
                    return result(null, 'Menu query exception', 0);
                }

            }else{

                $result_left = get_tree_left($menu::queryData(1, false, $user_id));

                if($result_left){
                    $data = [
                        'data_left' => $result_left,
                    ];
                    return result($data, 'Menu query success', 200);
                }else{
                    return result(null, 'Menu query exception', 0);
                }

            }
        }else{

            $menuList = $menu::queryData($is_menu);    

            $result = get_tree($menuList);

            foreach($result as $key => $value){
                // unset($result[$key]['parent_id']);
                unset($result[$key]['level']);
            }

            if($result){
                $data = [
                    'data' => $result,
                ];
                return result($data, 'Menu query success', 200);
            }else{
                return result(null, 'Menu query exception', 0);
            }

        }
        
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request, ModelMenu $menu)
    {
        // 获取数据
        $data = $request->param();

        // dump($data);

        if($menu->save($data)){
            return result(null,'save menu success',200);
        }else{
            return result(null,'save menu error',0);
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read(ModelMenu $menu,$id)
    {
        $result = $menu::field('title,parent_id,module_name,controller_name,view_name,is_menu,sorts')->find($id);

        if($result){
            return result($result,'read data success',200);
        }else{
            return result($result,'read data exception',0);
        }
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(ModelMenu $menu,Request $request, $id)
    {
        $data = $request->param('data');

        $menu = $menu::find($id);
        $menu->data($data,true);

        $result = $menu->save();

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
    public function delete(ModelMenu $menu,$id)
    {   

        if($id == 1){
            // return json([
            //     'code'  => 500,
            //     'msg'  => 'Are you sure you want to delete the main menu?'
            // ]);
            return result(null,'Are you sure you want to delete the main menu?',211);
        }
        // 判断删除当前菜单是否有下级菜单
        $idsData = $menu::where(['parent_id'=>$id])->field('id')->select()->toArray();

        if($idsData){

            // 将当前菜单插入到获取的菜单数据里面
            array_unshift($idsData,array('id'=>(int)$id));

            $ids = [];
            foreach($idsData as $val){
                $ids[] = $val['id'];
            }
            
            $ids = $ids;

        }else{

            $ids = $id;
        }

        $result = $menu::destroy($ids);


        // echo($result);

        if($result){
            return result(null,'delete data success',200);
        }else{
            return result(null,'delete data exception',200);
        }

    }

    /**
     * 删除指定选择资源 ids
     *
     * @param  int  $ids
     * @return \think\Response
     */
    public function deleteMenuForIds(ModelMenu $menu,Request $request,$ids){
        $ids = $request->param('ids');

        // 1 表示后台主菜单 系统管理 提示不让删除
        if(in_array(1,$ids)){
            return result(null,'Are you sure you want to delete the main menu?',211);
        }else{
            // 不存在主菜单就删除主菜单 以及子菜单
            foreach($ids as $val){

                $idsData = $menu::where(['parent_id'=>$val])->field('id')->select()->toArray();

                // 存在子菜单的情况  删除子菜单
                if($idsData){
                    // 将当前菜单插入到获取的菜单数据里面
                    array_unshift($idsData,array('id'=>(int)$val));

                    $ids = [];
                    foreach($idsData as $val){
                        $ids[] = $val['id'];
                    }
                    
                    $ids = $ids;

                }else{

                    $ids = $val;
                    
                }

                $result = $menu::destroy($ids);

            }

            if($result){
                return result(null,'delete data success',200);
            }else{
                return result(null,'delete data exception',200);
            }

        }
    }

    // 获取get_tree 数据
    public function getTree(ModelMenu $menu){
        // 获取是菜单所有的数据
        $menus = $menu::where(['is_menu'=>1])->order('sorts asc')->select()->toArray();

        $result = get_tree($menus);

        if($result){
            return result($result,'getTree query success',200);
        }else{
            return result(null,'getTree query error',0);
        }
    }
}
