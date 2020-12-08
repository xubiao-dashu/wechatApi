<?php
namespace app\index\controller;
use app\common\controller\Base;
use app\index\model\Faqtext;
use think\Db;
use think\Session;
class Faq extends Base
{
    //faq 内容详情
    public function faqList(){
       $list= Faqtext::all()->toArray();
       $this->assign('list',$list);
       return $this->view->fetch();
    }
    //编辑页
    public function faqEdit()
    {
        $id = $this->request->param('id');
        $data=Faqtext::get($id)->toArray();
        $this->assign('info',$data);
        return $this->view->fetch();

    }
    //执行编辑
    public function doEdit()
    {
        $data=$this->request->param();
        $user = new Faqtext();
        $res=$user->saveAll([$data]);
        if($res!=false){
            return ['msg'=>1];
        }else{
            return ['msg'=>0];
        }


    }

}