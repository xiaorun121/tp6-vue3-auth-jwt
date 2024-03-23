<?php

use think\facade\Route;

Route::group(function(){
    Route::get('index','index/index');
    Route::get('login','admin/login');
    Route::get('register','admin/register');
    Route::get('test','admin/test');
    Route::get('verify','admin/verify');
});