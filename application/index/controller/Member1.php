<?php
namespace app\index\controller;
use app\common\controller\Base;
use app\index\model\Member;
use think\Db;
use think\Session;
class Member1 extends Base
{
    //会员列表
    public function memberList()
    {
       $post =$this->request->param();
      
       $list = new Member;  
    
       if(isset($post['name'])){
        $name = $post['name'];   
        $data= Db::name('member')
        ->where('status','<',2)
        ->where('name','like',"%$name%")
        ->whereor('phone','like',"%$name%")
        ->whereor('nickname','like',"%$name%")
        ->field('id,nickname,headimg,status,password,FROM_UNIXTIME(create_time,"%Y-%m-%d %H:%i:%s") as create_time,name,phone')
        ->paginate(10); 
      }else{
        $data= Db::name('member')
        ->where('status','<',2)
        ->field('id,nickname,headimg,status,password,FROM_UNIXTIME(create_time,"%Y-%m-%d %H:%i:%s") as create_time,name,phone')
        ->paginate(10); 
        
      } 
      $data1= Member::all()->toArray();   
       // 获取分页显示
       $page = $data->render();
       $this->assign('list',$data);
       $this->assign('page',$page);
       $this->assign('count',count($data1));
      return $this->view->fetch();

    }
    //会员修改页
    public function memberEdit()
    {
     $id=$this->request->param('id');
     $info=Member::get($id)->toArray();
     $this->assign('info',$info);
     return $this->view->fetch();
    }
    //执行修改
    public function doEdit()
    {

        $data=$this->request->param();
        $user = new Member;
        $res=$user->saveAll([$data]);
        if($res!=false){
            return ['msg'=>1];
        }else{
            return ['msg'=>0];
        }
        // $member =Db::name('member')
        // ->where('phone','eq',$data['phone'])
        // ->select();
        // if($member==false){
        //     $user = new Member;
        //     $res=$user->saveAll([$data]);
        //     if($res!=false){
        //         return ['msg'=>1];
        //     }else{
        //         return ['msg'=>0];
        //     }
        // }else{
        //     return ['msg'=>0];
        // }
       
    }
    //会员添加页
    public function memberAdd()
    {
        return $this->view->fetch();
    }
    //执行添加会员
    public function doAdd()
    {
        $data = $this->request->param();
        $member =Db::name('member')
        ->where('phone','eq',$data['phone'])
        ->select();
        if($member==false){
            $user = new Member;
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

}