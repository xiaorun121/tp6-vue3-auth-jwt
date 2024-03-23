<?php
declare (strict_types = 1);

namespace app\admin\controller;

use app\admin\controller\Common;
use think\facade\View;

class Index extends Common
{
    public function index()
    {   
        return View::fetch(root_path().'/public/dist/index.html');
    }
}
