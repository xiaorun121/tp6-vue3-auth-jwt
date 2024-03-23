<?php
declare (strict_types = 1);

namespace app\api\model;

use app\api\validate\AdminValidate;
use think\exception\ValidateException;
use think\Model;
use think\model\concern\SoftDelete;
use app\api\model\AuthGroup;

/**
 * @mixin \think\Model
 */
class Admin extends Model
{   

    use SoftDelete;
    protected $deleteTime = 'delete_time';

    // 获取器
    public function getStatusAttr($value)
    {
        $status = [1=>true,0=>false];
        return $status[$value];
    }

    public function getIsAdminAttr($value)
    {
        $status = [1=>true,0=>false];
        return $status[$value];
    }


    // 获取用户组的标题
    public static function getAuthGroupTitle($id){
        $authGroup = new AuthGroup();
        return $authGroup::where(['id'=>$id])->value('title');
    }

    // 修改器
    public function setStatusAttr($value)
    {
        $result = ($value === true ? 1 : 0);
        return $result;
    }

    public function setIsAdminAttr($value)
    {
        $result = ($value === true ? 1 : 0);
        return $result;
    }

    // public function setPasswordAttr($value){
    //     return password_hash($value, PASSWORD_DEFAULT);
    // }

    // public function setSuperPasswordAttr($value){
    //     return password_hash($value, PASSWORD_DEFAULT);
    // }

    // 记录上次登录的ip
    public static function saveData($user){

        $admin = self::find($user['id']);
        
        // 第一次登录
        if($user['login_times'] == 0){
            // 记录 login_times login_ip last_login_ip login_time last_login_time
            $data['login_ip']    = request()->ip();
            $data['login_time']  = date('Y-m-d H:i:s',time());
        }

        $data['login_times']      = $user['login_times'] + 1;
        $data['last_login_ip']    = request()->ip();
        $data['last_login_time']  = date('Y-m-d H:i:s',time());


        $admin->save($data);

    }

    /**
     * 验证登录数据 
     */
    public static function checkLogin($data){

        // 验证数据
        try {

            validate(AdminValidate::class)->check($data);

        } catch (ValidateException $e) {

            return ['ret'=>500,'msg'=>$e->getError()];
        }
        
    }   

    /**
     * 查询数据
     */ 
    public static function queryUser($username, $password){

        $user =  self::where('username',$username)->find(); 

        if($user){

            $user = $user->toArray();

            $res = password_verify($password, $user['password']);

            if($res === false){

                return ['status'=> 0,'msg'=>'管理员跟密码不正确'];

            }else{
                if($user['status'] == 0){

                    return ['status'=>0,'msg'=>'账号被禁用，联系管理员'];
        
                }else{

                    // 登录成功 更新操作 login_times login_ip last_login_ip login_time last_login_time
                    self::saveData($user);

                    return ['status'=>1,'user_id'=>$user['id'],'isAdmin'=>$user['is_admin'],'username'=>$user['username'],'sex'=>$user['sex']];
                }
            }
            
        }else{
            
            return ['status'=>0,'msg'=>'该管理员不存在'];
        }
        
    }

    // 远程一对一 获取当前用户的所有权限 管理员获取所有的权限
    // 关联模型（必须）：关联模型类名
    // 中间模型（必须）：中间模型类名
    // 外键：默认的外键名规则是当前模型名+_id
    // 中间表关联键：默认的中间表关联键名的规则是中间模型名+_id
    // 当前模型主键：一般会自动获取也可以指定传入
    // 中间模型主键：一般会自动获取也可以指定传入
    public function rules(){
        return $this->hasOneThrough(AuthGroup::class,AuthGroupAccess::class,'uid','id','id','auth_group_id');
    }
}
