<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class School extends Model
{
    protected $table = 'school';
    protected $pk = 'id';

    // 设置字段信息
    protected $schema = [
        'id'          => 'int',
        'name'        => 'string',
        'city'        => 'string',
        'num'         => 'string'
    ];

    /**
     * 分页
     */

    public function getPage( $pageSize)
    {
        return self::paginate( $pageSize );
    }

    /**
     * 添加一条数据
     */

    public function addOne($data)
    {
        return self::insert( $data );
    }

    /**
     * 删除数据
     */

    public function delOne($where)
    {
        return self::where( $where )->delete();
    }

    /**
     * 修改
     */

    public function updOne($where,$data)
    {
        return self::where( $where )->update( $data );
    }

    /**
     * 查询一条
     */

    public function selOne($where)
    {
        return self::where( $where )->find();
    }
}
