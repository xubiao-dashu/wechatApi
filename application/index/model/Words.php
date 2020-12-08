<?php
namespace app\index\model;
use think\Model;
class Words extends Model
{
    protected $autoWriteTimestamp = true;//自动写入时间戳
    protected $resultSetType = 'collection';
    public function buy()
    {
    return $this->belongsTo('Buy','mh_buy_id');
    }
}