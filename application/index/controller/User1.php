<?php

namespace app\index\controller;
use app\common\controller\Base;
use app\index\model\User;
use think\Session;
use think\Db;
class User1 extends Base
{
    public function userList()
    {
        $list = User::all();
       // dump($list);
        $this->assign('list',$list);
        $this->assign('count',count($list));
        return  $this->view->fetch();
    }

    public function userAdd()
    {
        return  $this->view->fetch();
    }

    public function doUserAdd()
    {
        $data = $this->request->param();
        $user =Db::name('User')
        ->where('phone','eq',$data['phone'])
        ->select();
        if($user==false){
            $user = new User;
            $user->data(  $data );
           $res= $user->save();
            if($res!=false){
                return ['msg'=>1];
            }else{
                return ['msg'=>0];
            }    
        }else{
            return ['msg'=>0];
        }

    }
       //修改页
       public function userEdit()
       {
        $id=$this->request->param('id');
        $info=User::get($id)->toArray();
        $this->assign('info',$info);
        return $this->view->fetch();
       }
       //执行修改
       public function doEdit()
       {
   
           $data=$this->request->param();
           $user = new User;
           $res=$user->saveAll([$data]);
           if($res!=false){
               return ['msg'=>1];
           }else{
               return ['msg'=>0];
           }
        //    $member =Db::name('user')
        //    ->where('phone','eq',$data['phone'])
        //    ->select();
        //    if($member==false){
        //        $user = new User;
        //        $res=$user->saveAll([$data]);
        //        if($res!=false){
        //            return ['msg'=>1];
        //        }else{
        //            return ['msg'=>0];
        //        }
        //    }else{
        //        return ['msg'=>0];
        //    }
          
       }
    //启用、禁用
    public function setStatus()
    {
        $id = $this->request->param('id');
        $res = User::get($id);
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