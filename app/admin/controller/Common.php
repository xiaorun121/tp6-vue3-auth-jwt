<?php

declare (strict_types = 1);

namespace app\admin\controller;

use app\BaseController;

use wamkj\thinkphp\Auth;

class Common extends BaseController
{
    public function initialize(){

        $auth = new Auth();

        $controller = strtolower(request()->controller());
        $action = strtolower(request()->action());

        $url = $controller . "/" . $action;

        // check($url,1)   1 表示用户id
        // if(!$auth->check($url,1)){
        //     echo '抱歉，您没有操作权限';die;
        // }
    }
}