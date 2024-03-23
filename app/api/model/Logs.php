<?php
declare (strict_types = 1);

namespace app\api\model;

use think\Model;


/**
 * @mixin \think\Model
 */
class Logs extends Model
{
    // 获取器
    public function getUserIdAttr($user_id){
        $username = Admin::where(['id'=>$user_id])->value('username');

        if(!empty($username)){
            return $username;
        }
    }
}
