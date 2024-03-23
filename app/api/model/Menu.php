<?php
declare (strict_types = 1);

namespace app\api\model;

use think\facade\Cache;
use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class Menu extends Model
{   
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    // 获取器
    public function getIsMenuAttr($value)
    {
        $isMenu = [1=>true,0=>false];
        return $isMenu[$value];
    }

    // 修改器 
    public function setIsMenuAttr($value)
    {
        $result = ($value === true ? 1 : 0);
        return $result;
    }

    // 查询数据
    public static function queryData($is_menu, $isAdmin = 'true',$id = 1){

        if($is_menu == 1){

            // 判断是否是超级管理员  显示所有权限
            if($isAdmin == 'true'){
                $menu = self::where('is_menu',1)->order('sorts asc')->select()->toArray();

            }else{
                // 获取当前用户的权限
                $authGroup = Admin::find($id)->rules->toArray();

                $menu = self::where('is_menu',1)->where("id", "in", "{$authGroup['rules']}")->order('sorts asc')->select()->toArray();
            }

        }else{
            
            $menu = self::order('sorts asc')->select()->toArray();
            
        }

        return $menu;
    } 

    // 授权
    public static function authGroup(){
        $menu = self::field('title,is_menu,module_name,controller_name,view_name,create_time,update_time,sorts,id,parent_id')->order('sorts asc')->select()->toArray();

        foreach($menu as $key => $value){
            $menu[$key]['expand'] = true;
        }

        return $menu;
    } 
}
