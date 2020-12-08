<?php

namespace app\index\controller;
use app\common\controller\Base;
use app\index\model\User;
use think\Session;
use think\Db;
class Login extends Base
{


    //加载登录页面
    public function login()
    {
    $this->alreadyLogin();//判断是否已登录
    $info = Db::name('system')
        ->where('id',1)
        ->select()[0];
        // dump($info[0]);
    $this->assign('info',$info);
    return  $this->view->fetch();

    }
    //登录验证
    public function doLogin()
    {
       $get= $this->request->param();  
       if(trim($get['empphone'])==''){//字段验证
        return ['code'=>4];
      }
      if(trim($get['emppass'])==''){//字段验证
        return ['code'=>3];
      }
      $user = new User;
      $data=$user::get(['phone'=>$get['empphone']]);
      if($data['status']==1){
        $info=[];
        if($data['phone']==$get['empphone']){
          if($data['password']==$get['emppass']){
            $info=['count'=>$data->count,'login_time'=>date('Y-m-d h:i:s',$data->login_time),'phone'=>$data->phone,'password'=>$data->password,'username'=>$data->username,'id'=>$data->id,'role'=>$data->role,'status'=>$data->status];
            Session::set('info',$info,'think');//设置员工信息到session，存储登录信息
            if(isset($get['online'])){
            Session::set('line',1,'think');//设置员工信息到session，存储登录信息    
              }
            return ['code'=>2];//验证通过
          }else{
        return ['code'=>0];//密码错误
          }
        }else{ 
         return ['code'=>5];//手机号码不存在
        }

      }else{
        return ['code'=>6];//无权登录
      }
     }  
}