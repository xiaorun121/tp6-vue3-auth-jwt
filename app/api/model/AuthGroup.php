<?php
declare (strict_types = 1);

namespace app\api\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class AuthGroup extends Model
{
    //
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    // 获取器
    public function getStatusAttr($value)
    {
        $status = [1=>true,0=>false];
        return $status[$value];
    }

    // 修改器 
    public function setStatusAttr($value)
    {
        $result = ($value === true ? 1 : 0);
        return $result;
    }

    // 查询数据
    public static function queryData(){

        $authGroup = self::order('id asc')->select()->toArray();

        return $authGroup;
    } 
}
