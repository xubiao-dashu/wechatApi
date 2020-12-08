<?php
namespace app\index\controller;
use app\common\controller\Base;
use think\Db;
use think\Session;
class Zhan extends Base
{
    //网站基础设置页
    public function zhanList()
    {
        $info = Db::name('system')
        ->where('id',1)
        ->select()[0];
        // dump($info[0]);
        $this->assign('info',$info);
        return $this->view->fetch();
    }
    //更新
    public function update()
    {
        $data=$this->request->param();
        $res=Db::table('mh_system')
        ->update($data);        
        if($res==1){
            return ['msg'=>1];
        }else{
            return ['msg'=>0];
        }

    }

}