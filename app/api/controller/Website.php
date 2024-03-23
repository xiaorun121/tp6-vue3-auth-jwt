<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\api\model\Website as ModelWebsite;
use app\BaseController;
use app\Request;

class Website extends BaseController
{
    // 获取website 数据
    public function getWebsite(ModelWebsite $website){
        $result = $website::field('name,url,title,keywords,description,copyright')->find(1)->toArray();

        if($result){
            return result($result,'website query success',200);
        }else{
            return result($result,'website query exception',0);
        }
    }

    // 保存数据
    public function saveWebsite(Request $request, ModelWebsite $website){
        $data = $request->param();

        $result = $website::update($data,['id'=>1]);

        if($result){
            return result($result,'save data success',200);
        }else{
            return result($result,'save data exception',200);
        }
    }
}
