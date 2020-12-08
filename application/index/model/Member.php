<?php
namespace app\index\model;
use think\Model;
class Member extends Model
{
    protected $autoWriteTimestamp = true;//自动写入时间戳
    protected $resultSetType = 'collection';

    public function getStatusAttr($value)
    {
        $status=[1=>'正常',0=>'禁用'];
        return $status[$value];
    }  
}