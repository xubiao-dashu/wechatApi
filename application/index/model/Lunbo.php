<?php
namespace app\index\model;
use think\Model;
class Lunbo extends Model
{
    protected $autoWriteTimestamp = true;//自动写入时间戳
    protected $resultSetType = 'collection';

    public function getStatusAttr($value)
    {
        $status=[1=>'已发布',0=>'已下架'];
        return $status[$value];
    }      
}
