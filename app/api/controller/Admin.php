<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\api\model\Admin as ModelAdmin;
use app\api\model\AuthGroupAccess;
use app\BaseController;
use think\Request;
use thans\jwt\facade\JWTAuth;
use think\captcha\facade\Captcha;
use think\facade\Cache;
use think\facade\Db;
use think\facade\Session;

class Admin extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(ModelAdmin $admin,Request $request)
    {
        $data = $request->param();

        $page = $data['page'];
        $pageSize = $data['pageSize'];

        $result = $admin->paginate($pageSize,false,[
            'page' => $page
        ]);

        $resultInfo = $result->toArray();

        foreach($resultInfo['data'] as $key => $value){
            $resultInfo['data'][$key]['auth_group_id'] = $admin::getAuthGroupTitle($value['auth_group_id']);
        }

        if($resultInfo){

            if(empty($resultInfo['data'])){
                return result(['page'=>$page],'query data not found',210);
            }
            
            return result($resultInfo,'query data success',200);
        }else{
            return result(null,'query data error',0);
        }
    }

    // 获取发布人员信息
    public function getPublish(ModelAdmin $admin){
        $admins = $admin->where('status',1)->field('id,username')->order('id asc')->select()->toArray();
        if($admins){
            return result($admins,'query data success',200);
        }else{
            return result(null,'query data error',0);
        }
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request,ModelAdmin $admin)
    {
        $data = $request->param();
        $data['password'] = password_hash($data['password'],PASSWORD_DEFAULT);

        // 启动事务
        Db::startTrans();
        try {

            $result = $admin::create($data);
            $uid = $result->id;

            // 提交过来的数据 is_admin选择的话  就有数据  没选的话就得不到这个数据
            if(!isset($data['is_admin'])){

                $auth_group_id = $data['auth_group_id'];

                $authGroupAccessData = [
                    'uid' => $uid,
                    'auth_group_id' => $auth_group_id
                ];
                AuthGroupAccess::create($authGroupAccessData);
            }

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }

        if($result){
            return result($data,'save data success',200);
        }else{
            return result(null,'save data error',0);
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read(ModelAdmin $admin,$id)
    {
        $result = $admin::find($id)->toArray();

        if($result){
            return result($result,'for Id query data success',200);
        }else{
            return result($result,'for Id query data error',0);
        }
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request,ModelAdmin $admin, $id)
    {
        $data = $request->param('data');
        $auth_group_id = $data['auth_group_id'];

        // 启动事务
        Db::startTrans();
        try {

            $admin = $admin::find($id);
            $admin->data($data,true);
            $result = $admin->save();

            // 提交过来的数据是全部的数据 is_admin = true /false
            if($data['is_admin'] === false){

                $authGroupAccess = AuthGroupAccess::where(['uid'=>$id])->find();
                if($authGroupAccess){
                    // 存在及更新
                    AuthGroupAccess::update(['auth_group_id'=>$auth_group_id],['uid'=>$id]);
                }else{
                    // 不存在及新增
                    $authGroupAccessData = [
                        'uid' => $id,
                        'auth_group_id' => $auth_group_id
                    ];
                    AuthGroupAccess::create($authGroupAccessData);
                }
            }

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }

        
        
        if($result){
            return result($data,'save data success',200);
        }else{
            return result(null,'save data error',0);
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete(ModelAdmin $admin, $id)
    {
        $result = $admin::destroy($id);

        if($result){
            return result(null,'delete data success',200);
        }else{
            return result(null,'delete data exception',200);
        }
    }

    /**
     *  登录操作
     *  
     * @return \think\Response
     */ 
    public function login(Request $request, ModelAdmin $admin){

        // echo password_hash('1',PASSWORD_DEFAULT);

        // 检测输入的验证码是否正确
        $captcha = $request['captcha'];
        
        if(!Captcha::check($captcha)){
            // 验证失败
            return json([
                'code'    => 0,
                'status'  => 'error',
                'msg'     => '验证码错误'
            ]);
        }

        $data = [
            'username' => $request['username'],
            'password' => $request['password']
        ];

        if($request->isPost()){
            
            // 验证登录是否有用户名和密码
            $result = $admin::checkLogin($data);

            if($result){

                // 输出验证信息
                return $result;

            }else{
                
                // 执行查询登录信息 获取用户id
                $user = $admin::queryUser($request['username'],$request['password']);

                // status 0 被禁用
                if($user['status'] == 0){

                    return json([
                        'ret' => 404,
                        'msg' => $user['msg']
                    ]);

                }else{

                    $data = array('uid'=>$user['user_id']);

                    // 生成token，参数为用户认证的信息，请自行添加

                    Cache::set('uid', $user['user_id'], 60 * 60 * 24);
                    
                    $token = JWTAuth::builder($data);

                    $data = [
                        'token'    => 'Bearer ' . $token,
                        'ttl'      => env('JWT_TTL'),
                        'userinfo' => [
                            'user_id'  => $user['user_id'],
                            'isAdmin'  => $user['isAdmin'],
                            'username' => $user['username'],
                            'sex'      => $user['sex']
                        ]
                        
                    ];

                    if(!$user['isAdmin']){
                        $data['authGroup'] = $admin::find($user['user_id'])->rules;
                    }

                    return result($data,'login success',201);
                }

            }
        }
    }

    /**
     * 验证码 
     * 
     */
    public function verify(){

        return Captcha::create();
    }

    // 重置密码
    public function resetPassword(ModelAdmin $admin,$id){

        $data['password'] = password_hash("1", PASSWORD_DEFAULT);
        
        $admin = $admin::find($id);

        $admin->data($data,true);

        $result = $admin->save();

        if($result){
            return result(null,'password reset success, password is 1',200);
        }else{
            return result(null,'password reset error',0);
        }
    }

    // 修改管理员状态
    public function saveAdminStatus(ModelAdmin $admin, Request $request){
        $data = $request->param();

        $result = $admin::update(
            ['status'=>$data['status']],
            ['id'=>$data['id']]
        );

        if($data['status'] === true){
            $msg = 'status open';
        }else{
            $msg = 'status close';
        }

        if($result){
            return result(null,$msg,200);
        }else{
            return result(null,'status change error',0);
        }
    }

    // 修改管理员状态
    public function saveAdminIsAdmin(ModelAdmin $admin, Request $request){
        $data = $request->param();

        $result = $admin::update(
            ['is_admin'=>$data['is_admin']],
            ['id'=>$data['id']]
        );

        if($data['is_admin'] === true){
            $msg = 'isAdmin open';
        }else{
            $msg = 'isAdmin close';
        }

        if($result){
            return result(null,$msg,200);
        }else{
            return result(null,'is_admin change error',0);
        }
    }

    // 验证权限
    public function checkAuth(Request $request,ModelAdmin $admin){

        // 获取当前菜单的id
        $menu_id = $request->param('id');

        // 用户id
        $user_id = $request->param('user_id');

        // 获取当前用户的权限数据
        $authGroup = $admin::find($user_id)->rules->toArray();

        $rules = $authGroup['rules'];

        if(strpos($rules, $menu_id) !== false){
            return result(null, 'the menu is Auth', 200);
        }else{
            return result(null, 'the menu is not Auth' ,0);
        }
        
    }

    // 判断是否有按钮权限
    public function checkIsAuthToButton(Request $request,ModelAdmin $admin){

        // 获取当前菜单按钮的id
        $menu_id = strval($request->param('menu_auth_id'));

        // 用户id
        $user_id = $request->param('user_id');

        // 获取当前用户的权限数据
        $authGroup = $admin::find($user_id)->rules->toArray();

        $rules = $authGroup['rules'];

        if(strpos(','.$rules.',', ','.$menu_id.',') !== false){
            return result(null, 'With permission', 200);
        }else{
            return result(null, 'No permission' ,0);
        }
    }

    // 获取用户的规则数据rules
    public function getUserToRules(Request $request,ModelAdmin $admin){

        // 用户id
        $user_id = $request->param('user_id');

        // 获取当前用户的权限数据
        $authGroup = $admin::find($user_id)->rules->toArray();

        $rules = $authGroup['rules'];

        if($authGroup){
            return result($rules, 'query user auth success', 200);
        }else{
            return result(null, 'query user auth error' ,0);
        }
    }
}
