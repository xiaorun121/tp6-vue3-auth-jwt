<?php

declare (strict_types = 1);

namespace app\admin\controller;

use think\captcha\facade\Captcha;

use app\admin\controller\Common;

class Admin extends Common
{
    public function login(){
        return 'admin/login';
    } 

    public function register(){
        return 'admin/register';
    } 

    public function test(){
        return 'admin/test';
    }

     /**
     * 验证码 
     * 
     */
    public function verify(){
        return Captcha::create();  
    }
}