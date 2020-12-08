<?php
namespace app\index\model;
use think\Model;
class User extends Model
{
    protected $autoWriteTimestamp = true;//自动写入时间戳

    public function getRoleAttr($value)
    {
        $role=[1=>'超级管理员',2=>'管理员'];
        return $role[$value];
    }
    // public function getStatusAttr($value)
    // {
    //     $status=[1=>'正常',0=>'禁用'];
    //     return $status[$value];
    // }      
}
