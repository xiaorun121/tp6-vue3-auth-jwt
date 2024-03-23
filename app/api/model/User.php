<?php
declare (strict_types = 1);

namespace app\api\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class User extends Model
{
    protected $name = 'admin';

    protected $autoWriteTimestamp = true;
}
