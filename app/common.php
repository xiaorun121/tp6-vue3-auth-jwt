<?php
// 应用公共文件
use think\Response;
use app\api\model\Menu;
use think\facade\Db;

if(!function_exists('result')){
    function result($data = [], string $msg = 'error', int $code = 0, string $type = 'json'):Response {
        $result = [
            "code" => $code,
            "msg"  => $msg,
            'data' => $data
        ];

        return Response::create($result, $type)->code($code);
    }
}


// 树形菜单
if(!function_exists('get_tree')){
    function get_tree($data, $parent_id = 0, $level = 0) {
        static $arr = array();
        foreach ($data as $d) {
            if ($d['parent_id'] == $parent_id) {
                $d['level'] = $level;
                if($level > 0){
                    $d['title']  = html_entity_decode(str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',$level).$d['title']);
                }
                $arr[] = $d;
                get_tree($data, $d['id'], $level + 1);
            }
        }
    
        return $arr;
    }
}

// 二级菜单 is_menu 1
if(!function_exists('get_tree_left')){
    function get_tree_left($data, $parent_id = 0){
        static $arr = array();
        foreach($data as $d){

            if($d['parent_id'] == $parent_id){
                
                // 当前设定为超级管理员，可以查看所有的权限菜单
                $menu =  (new Menu())->where('parent_id',$d['id'])->where('is_menu',1)->order('sorts asc')->select();

                foreach ($menu as $key => $value) {
                    $menu[$key] = $value->toArray();
                }

                /**
                 * 遇到的大坑 think\Model\Collection 
                 * 下面凡是涉及到多条数据 要使用all() 转array    单挑数据要使用toArray() 转array
                 */ 

                // 二级菜单
                if(!empty($menu->toArray())){
                    $d['children'] = $menu->all();   // 这里 all() 
                }

                // 三级菜单
                foreach($menu->all() as $kk => $val){    // 这里 all()
                    $menu_child =  (new Menu())->where('parent_id',$val['id'])->where('is_menu',1)->order('sorts asc')->select()->all();

                    if(!empty($menu_child)){
                        foreach ($menu_child as $k => $v) {
                            $menu_child[$k] = $v->toArray();  // 这里 toArray()
                        }
                        $d['children'][$kk]['child'] = $menu_child;
                    }
                }
                
                $arr[] = $d;
                get_tree($data, $d['id']);
            }
            
        }

        return $arr;
    }
}

// 三级菜单 expand:true  给Tree控件加展开属性
if(!function_exists('get_tree_three')){
    function get_tree_three($data, $parent_id = 0){
        static $arr = array();
        foreach($data as $d){

            if($d['parent_id'] == $parent_id){
                
                // 当前设定为超级管理员，可以查看所有的权限菜单
                $menu =  (new Menu())->where('parent_id',$d['id'])->order('sorts asc')->select();

                foreach ($menu as $key => $value) {
                    $menu[$key]['expand'] = true;       // 1 expand:true
                    $menu[$key] = $value->toArray();
                }

                /**
                 * 遇到的大坑 think\Model\Collection 
                 * 下面凡是涉及到多条数据 要使用all() 转array    单挑数据要使用toArray() 转array
                 */ 

                // 二级菜单
                if(!empty($menu->toArray())){
                    $d['children'] = $menu->all();   // 这里 all() 
                }


                // 三级菜单
                foreach($menu->all() as $kk => $val){    // 这里 all()
                    $menu_child =  (new Menu())->where('parent_id',$val['id'])->order('sorts asc')->select()->all();

                    if(!empty($menu_child)){
                        foreach ($menu_child as $k => $v) {
                            $menu_child[$k]['expand'] = true;  // 2 expand:true
                            $menu_child[$k] = $v->toArray();  // 这里 toArray()
                        }
                        $d['children'][$kk]['children'] = $menu_child;
                    }
                }
                
                $arr[] = $d;
                get_tree($data, $d['id']);
            }
            
        }

        return $arr;
    }
}

// 三级菜单 expand:true  给Tree控件加展开属性 并且得出是否是选中的状态 checked true
if(!function_exists('get_tree_three_isChecked')){
    function get_tree_three_isChecked($data, $parent_id = 0,$rules){
        static $arr = array();
        foreach($data as $d){

            if(in_array($d['id'],$rules)){
                $d['checked'] = true; 
                
            }else{
                $d['checked'] = false; 
            }

            if($d['parent_id'] == $parent_id){
                
                // 当前设定为超级管理员，可以查看所有的权限菜单
                $menu =  (new Menu())->where('parent_id',$d['id'])->order('sorts asc')->select();

                foreach ($menu as $key => $value) {
                    $menu[$key]['expand'] = true;       // 1 expand:true
                    if(in_array($value->id,$rules)){
                        $menu[$key]['checked'] = true; 
                    }else{
                        $menu[$key]['checked'] = false;
                    }
                    $menu[$key] = $value->toArray();
                }

                /**
                 * 遇到的大坑 think\Model\Collection 
                 * 下面凡是涉及到多条数据 要使用all() 转array    单挑数据要使用toArray() 转array
                 */ 

                // 二级菜单
                if(!empty($menu->toArray())){
                    $d['children'] = $menu->all();   // 这里 all() 
                }


                // 三级菜单
                foreach($menu->all() as $kk => $val){    // 这里 all()
                    $menu_child =  (new Menu())->where('parent_id',$val['id'])->order('sorts asc')->select()->all();

                    if(!empty($menu_child)){
                        foreach ($menu_child as $k => $v) {
                            $menu_child[$k]['expand'] = true;  // 2 expand:true
                            if(in_array($v->id,$rules)){
                                $menu_child[$k]['checked'] = true; 
                            }else{
                                $menu_child[$k]['checked'] = false; 
                            }
                            $menu_child[$k] = $v->toArray();  // 这里 toArray()
                        }
                        $d['children'][$kk]['children'] = $menu_child;
                    }
                }
                
                $arr[] = $d;
                get_tree($data, $d['id']);
            }
            
        }

        return $arr;
    }

    // 获取更新的批次数据
    function getAppTraceUpdate($app_id){

        $count = Db::name("app_trace_update")->where('app_id',$app_id)->count();

        if($count == 0){

            return 0;
        }else{

            return $count;
        }
    }
}
