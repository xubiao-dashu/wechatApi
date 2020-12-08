<?php
namespace app\index\controller;
use app\common\controller\Base;
use app\index\model\User;
use app\index\model\Buy;
use app\index\model\Lunbo;
use app\index\model\Member;
use app\index\model\Faqtext;
use app\index\model\Sell;
use app\index\model\Words;
use think\Db;
use think\Session;
class Index extends Base
{
    public function index()
    {
       $this->isLogin();//判断是否登录
       $user = User::get(Session::get('info','think')['id']);
       $user->count =$user->count+1;//登录次数自增1
       $user->login_time = time();//记录本次登陆时间
       $user->save();
       $info = Db::name('system')
       ->where('id',1)
       ->select()[0];
       // dump($info[0]);
       $this->assign('info',$info);
       $this->assign('userinfo',Session::get('info','think'));
       $this->assign('title','首页');
       return  $this->view->fetch();
    }
    //关闭页面时清除登录状态，前提是用户没有保持登录状态
    public function clearLogin()
    {
        if(!Session::has('line','think')){
            Session::delete('info','think');
        }   
    }

    //退出登录,清除登录状态
    public function outLogin()
    {
        Session::delete('info','think');
        $this->success('正在退出...','index/Login/login');
    }
    public function welcome()
    {
        $count=User::where('status','<',2)->count();//用户总数
        $count1=Member::where('status','<',2)->count();//会员总数
        $count2=Buy::where('status','<',2)->count();//求购总数
        $count3=Sell::where('status','<',2)->count();//供货总数
        $count4=Words::where('status','<',2)->count();//供货总数
        $info = Db::name('system')//网站配置信息
        ->where('id',1)
        ->select()[0];

        $this->assign('info',$info);
        $this->assign('usercount',$count);
        $this->assign('membercount',$count1);
        $this->assign('buycount',$count2);
        $this->assign('sellcount',$count3);
        $this->assign('wordscount',$count4);
        $this->assign('userinfo',Session::get('info','think'));
        return  $this->view->fetch('\welcome\welcome');
    }
}
