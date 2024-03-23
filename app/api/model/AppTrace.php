<?php

declare(strict_types=1);

namespace app\api\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class AppTrace extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    /**
     * 获取器
     */ 
    // 类型
    public function getAppTypeAttr($value)
    {
        $appType = [1 => 'H5',2 => '原生',3 => 'UNIAPP', 4 => 'H5+TP',5 => 'IOS',6 => 'Unity',7 => 'Cocos'];

        if(!empty($value)){
            return $appType[$value];
        }
        
    }

    // 状态
    public function getStatusAttr($value)
    {
        $status = [
            1 => '已上架', 2 => '审核中', 3 => '待审核', 4 => '账号禁用', 5 => '分配中', 6 => '已暂停', 7 => '待验证', 8 => '已下架', 9 => '账号关联', 10 => '更新中', 11 => '其他'
        ];

        if(!empty($value)){
            return $status[$value];
        }

        
    }

    // 跳转开关
    public function getJumpSwitchAttr($value){

        if(empty($value)){
            return false;
        }else{
            $jumpSwitch = ['on' => true, 'off' => false];
            return $jumpSwitch[$value];
        }
    }

    // 热更新开关
    public function getUpdateSwitchAttr($value){

        if(empty($value)){
            return false;
        }else{
            $updateSwitch = ['on' => true, 'off' => false];
            return $updateSwitch[$value];
        }
    }

    // HTTPS 启用状态开关
    public function getHttpsStatusAttr($value){

        if(empty($value)){
            return false;
        }else{
            $updateSwitch = [1 => true, 0 => false];
            return $updateSwitch[$value];
        }
    }

    // 域名验证
    public function getGoogleSharchStatusAttr($value){
        if(empty($value)){
            return false;
        }else{
            $googleSharchStatus = [1 => true, 0 => false];
            return $googleSharchStatus[$value];
        }
    }

    // 讨论组id
    public function getTeleChatidAttr($value){
        if(empty($value)){
            return '';
        }else{
            return explode(",", $value);
        }
    }

    /**
     * 修改器 
     */ 
    // 跳转开关
    public function setJumpSwitchAttr($value){
        $jumpSwitch = [true => 'on', false => 'off'];
        return $jumpSwitch[$value];
    }

    // 热更新开关
    public function setUpdateSwitchAttr($value){

        if(empty($value)){
            return false;
        }else{
            $updateSwitch = [true => 'on', false => 'off'];
            return $updateSwitch[$value];
        }
    }

    // 讨论组id
    public function setTeleChatidAttr($value){
        if(empty($value)){
            return '';
        }else{
            return implode(",", $value);
        }
    }

    // 类型
    public function setAppTypeAttr($value)
    {
        $appType = ['H5' => 1,'原生' => 2,'UNIAPP' => 3, 'H5+TP' => 4,'IOS' => 5 ,'Unity' => 6 , 'Cocos' => 7];

        if(!empty($value)){
            return $appType[$value];
        }
        
    }

    // 状态
    public function setStatusAttr($value)
    {
        $status = [
            '已上架' => 1, '审核中' => 2, '待审核' => 3, '账号禁用' => 4, '分配中' => 5, '已暂停' => 6, '待验证' => 7, '已下架' => 8, '账号关联' => 9, '更新中' => 10, '其他' => 11
        ];

        return $status[$value];
    }
}
