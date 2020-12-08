<?php
namespace app\index\controller;
use app\common\controller\Base;
use app\index\model\Buy;
use think\Db;
use think\Session;
class Buy1 extends Base
{
    //求购列表
    public function buyList()
    {
        $post =$this->request->param();
        if(isset($post['title'])){
            $title =$post['title'];
            $data = Db::name('buy')
           // ->where('status','<',2)
            ->where('title','like',"%$title%")
            ->field('id,title,content,province,city,county,address,status,FROM_UNIXTIME(create_time,"%Y-%m-%d %H:%i:%s") as create_time,name,phone')
            ->paginate(10);
        }else{
            $data = Db::name('buy')->where('status','<',2)
            ->field('id,title,content,province,city,county,address,status,FROM_UNIXTIME(create_time,"%Y-%m-%d %H:%i:%s") as create_time,name,phone')
            ->paginate(10);
        }
       
       //  dump($data);
        $this->assign('list',$data);
        // $this->assign('count',$data['total']);
        // $this->assign('current',$data['current_page']);
        return $this->view->fetch();
    }
    //修改页
    public function buyEdit()
    {
        $id=$this->request->param('id');
        $info=Buy::get($id)->toArray();
        $this->assign('info',$info);
        return $this->view->fetch();

    }
        //执行修改
        public function doEdit()
        {
            $data=$this->request->param();
            $user = new Buy;
            $res=$user->saveAll([$data]);
            if($res!=false){
                return ['msg'=>1];
            }else{
                return ['msg'=>0];
            }
    
    
        }
        //添加页
        public function buyAdd()
        {
            return $this->view->fetch();

        }
        //执行添加
        public function doAdd()
        {
            $data = $this->request->param();
            $user = new Buy;
            $user->data(  $data );
            $res= $user->save();
            if($res!=false){
                return ['msg'=>1];
            }else{
                return ['msg'=>0];
            }
    
    
        }
      //启用、禁用
      public function setStatus()
      {
          $id = $this->request->param('id');
          $res = Buy::get($id);
          if($res->status==1){
              $res->status=0;
              $get=$res->save();
              if($get!=false){
                  return ['msg'=>1];
              }else{
                  return ['msg'=>0];
              }
          }else{
              $res->status=1;
              $get=$res->save();
              if($get!=false){
                  return ['msg'=>1];
              }else{
                  return ['msg'=>0];
              }  
          }
  
      }
    
}