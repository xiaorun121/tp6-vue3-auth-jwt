<?php

use think\facade\Route;
use think\middleware\Throttle;



// Route::post('v1/login','admin/login')->allowCrossDomain([
//     'Access-Control-Allow-Headers' => 'Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With'
// ]);

Route::group('v1', function () {
    Route::get('user', 'admin/user')->allowCrossDomain();
})->middleware(Throttle::class, ['visit_rate' => '100/m',])->middleware(\app\api\middleware\Jwt::class);


Route::group('v1', function () {

    // menu
    Route::resource('menu', 'menu');

    // 删除指定选择资源 ids
    Route::post('deleteMenuForIds', 'menu/deleteMenuForIds');

    // 获取get_tree数据
    Route::get('getTree', 'menu/getTree');


    // website
    Route::group(function () {
        Route::get('/getWebsite', 'website/getWebsite')->name('getWebsite');
        Route::post('/saveWebsite', 'website/saveWebsite')->name('saveWebsite');
    });

    // authGroup
    Route::resource('authGroup', 'authGroup');

    // user
    Route::resource('user', 'user');

    // admin
    Route::resource('admin', 'admin');

        // 重置密码
        Route::post('resetPassword','admin/resetPassword');

        // 保存用户状态
        Route::post('saveAdminStatus','admin/saveAdminStatus');
        
        // 保存用户是否是管理员状态
        Route::post('saveAdminIsAdmin','admin/saveAdminIsAdmin');

        // 验证权限
        Route::post('checkAuth', 'admin/checkAuth');

        //判断是否有按钮权限
        Route::post('checkIsAuthToButton','admin/checkIsAuthToButton');

        // 获取用户的规则数据rules
        Route::post('getUserToRules','admin/getUserToRules');

        // 获取发布人员信息
        Route::get('getPublish','admin/getPublish');

    // chat
    Route::resource('chat', 'chat');

    // appTrace
    Route::resource('appTrace', 'appTrace');

        // 删除图片
        Route::post('delImage', 'appTrace/delImage');

        // 一键复制
        Route::post('isCopy','appTrace/isCopy');

        // 根据id查看更新原因
        Route::post('readUpdateBatchToId','appTrace/readUpdateBatchToId');
    
    // 项目组数据汇总
    Route::get('projectGather','projectGather/index');
    // 显示项目组数据列表数据
    Route::get('showProject','projectGather/showProject');

    // logs
    Route::get('logs','logs/index');

    // 实时更新数据
    Route::get('liveUpdateLogs','liveUpdateLogs/index');

    // 采集后台管理
    Route::resource('backend', 'backend');

    // 采集数据
    Route::resource('gather', 'gather');
        

})->middleware(\app\api\middleware\Jwt::class);

Route::group('v1', function () {
    // 验证码
    Route::get('verify', 'admin/verify');
    // 登录
    Route::post('login', 'admin/login');

    // 上传图片
    Route::post('uploadImage','appTrace/uploadImage');

    // 上传编辑器图片
    Route::any('uploadEditorImage','gather/uploadEditorImage');
    
});
