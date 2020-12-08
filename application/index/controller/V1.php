<?php
namespace app\index\controller;

use app\common\controller\Base;
use app\index\model\Buy;
use app\index\model\Lunbo;
use app\index\model\Member;
use app\index\model\Faqtext;
use app\index\model\Sell;
use app\index\model\Words;
use app\index\model\User;
use think\Db;
use think\Session;
use think\Request;

/**
     * 前台接口
     */
class V1 extends Base
{

    //上传图片的接口
    public function upload()
    {
        $request = Request::instance();
        $file = request()->file('file');
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->validate(['size'=>20000000,'ext'=>'jpg,png,jpeg,gif'])->move('../public/static/sns/images', time());
        $self =$_SERVER['PHP_SELF'];//获取网页地址
        $self1  =  strstr($self, 'index.php', true);  // 截取index.php之前的路径
        $fix=$request->domain().$self1."static/sns/images/";//访问前缀（当前域名和截取后的路径拼接得完整路径）
        if ($info) {
            return json(['code'=>200,'imgurl'=>$fix.$info->getSaveName()]);
        } else {
            // 上传失败获取错误信息
            return json(['code'=>400,'msg'=>$file->getError()]);
        }
    }
    //会员注册
    public function register()
    {
        $data = $this->request->param();
        if (isset($data['phone'])) {
            $member =Db::name('member')
            ->where('phone', 'eq', $data['phone'])
            ->select();
            if ($member==false) {
                $user = new Member;
                $user->data($data);
                $res= $user->save();
                if ($res!=false) {
                    return json(['code'=>200,'msg'=>'add success']);
                } else {
                    return json(['code'=>400,'msg'=>'add fail']);
                }
            } else {
                return json(['code'=>400,'msg'=>'phone is exist']);
            }
        } else {
            return json(['code'=>400,'msg'=>'phone is required']);
        }
    }
    //会员登录
    public function login()
    {
        $data = $this->request->param();
        if (isset($data['phone'])&&isset($data['password'])) {
            $member =Db::name('member')
            ->where('phone', 'eq', $data['phone'])
            ->select();
            //dump($member);
            if ($member!=false) {
                if ($member[0]['password']==$data['password']) {
                    Session::set('uuid', $member[0]);//记录会员信息
                    return json(['code'=>200,'msg'=>'login success']);
                } else {
                    return json(['code'=>400,'msg'=>'password error']);
                }
            } else {
                return json(['code'=>400,'msg'=>'phone not exist']);
            }
        } else {
            return json(['code'=>400,'msg'=>'phone and password is required']);
        }
    }
    //检测用户是否登录
    public function isLogin()
    {
        if (Session::has('uuid')) {
            return true;
        } else {
            return false;
        }
    }
    //轮播图接口
    public function lunList()
    {
        $data = Lunbo::all(function ($query) {
            $query->where('status', 'egt', 1)->order(['sort'=>'asc']);
        });
        return json_encode($data);
    }
    // 音频列表接口
    public function voiceList()
    {
        $data = Lunbo::all(function ($query) {
            $query->where('type', 'egt', 2)->order(['sort'=>'asc']);
        });
        return json_encode($data);
    }
    //最新求购接口
    public function buyList()
    {
        $data = Db::name('buy')->where('status', 'eq', 1)
        ->order(['id'=>'desc'])
        ->field('id,title,content,address,status,FROM_UNIXTIME(create_time,"%Y-%m-%d %H:%i:%s") as create_time,name,phone')
        ->paginate(6);
        return json_encode($data);
    }
    //最新供货接口
    public function sellList()
    {
        $data = Db::name('sell')->where('status', 'eq', 1)
           ->order(['id'=>'desc'])
           ->field('id,title,content,address,status,FROM_UNIXTIME(create_time,"%Y-%m-%d %H:%i:%s") as create_time,name,phone')
           ->paginate(6);
        return json_encode($data);
    }
    //求购查询 接口参数：title 标题
    public function findBuy()
    {
        $data = $this->request->param();
        if (isset($data['title'])) {
            // $data = Db::name('buy')->where('title','eq',$data['title'])
            // ->select();
            $data =Db::query('select id,title,content,address,status,FROM_UNIXTIME(create_time,"%Y-%m-%d %H:%i:%s") as create_time,name,phone from mh_buy where title like "%'.$data['title'].'%"');
            return json($data);
        } else {
            return json(['code'=>400,'msg'=>'fail']);
        }
    }
    //供货查询 接口参数：title 标题
    public function findSell()
    {
        $data = $this->request->param();
        if (isset($data['title'])) {
            // $data = Db::name('buy')->where('title','eq',$data['title'])
            // ->select();
            $data =Db::query('select id,title,content,address,status,FROM_UNIXTIME(create_time,"%Y-%m-%d %H:%i:%s") as create_time,name,phone from mh_sell where title like "%'.$data['title'].'%"');
            return json($data);
        } else {
            return json(['code'=>400,'msg'=>'search fail']);
        }
    }
    //会员信息 接口参数：phone 电话号码
    public function memberInfo()
    {
        if ($this->isLogin()) {//检测会员的登录状态
            $data = $this->request->param();
            if (isset(Session::get('uuid')['phone'])) {
                // $data = Db::name('buy')->where('title','eq',$data['title'])
                // ->select();
                $data =Db::query('select nickname ,headimg,name,phone from mh_member where phone='.Session::get('uuid')['phone']);
                return json($data);
            } else {
                return json(['code'=>400,'msg'=>'search fail']);
            }
        } else {
            return json(['code'=>400,'msg'=>'no login']);
        }
    }
    //会员信息修改
    public function editInfo()
    {
        if ($this->isLogin()) {//检测会员的登录状态
            $data = $this->request->param();
            $user = new Member();
            // 过滤post数组中的非数据表字段数据
            $res= $user->allowField(true)->save($data, ['id' =>Session::get('uuid')['id']]);
            if ($res!=false) {
                return json(['code'=>200,'msg'=>'update success']);
            } else {
                return json(['code'=>400,'msg'=>'update fail']);
            }
        } else {
            return json(['code'=>400,'msg'=>'no login']);
        }
    }
          
    //求购列表->分页 接口参数：num 页码
    public function buyListFen()
    {
        $res = $this->request->param();
        if (isset($res['num'])) {
            $num=$res['num'];
            $data = Db::name('buy')->where('status', 'eq', 1)
                ->field('id,title,content,province,city,county,address,status,FROM_UNIXTIME(create_time,"%Y-%m-%d %H:%i:%s") as create_time,name,phone')
                ->page("$num,2")->select();
            return json($data);
        } else {
            return json(['code'=>400,'msg'=>'fail']);
        }
    }

    //供货列表->分页 接口参数：num 页码
    public function sellListFen()
    {
        $res = $this->request->param();
        if (isset($res['num'])) {
            $num=$res['num'];
            $data = Db::name('sell')->where('status', 'eq', 1)
                ->field('id,title,content,province,city,county,address,status,FROM_UNIXTIME(create_time,"%Y-%m-%d %H:%i:%s") as create_time,name,phone')
                ->page("$num,2")->select();
            return json($data);
        } else {
            return json(['code'=>400,'msg'=>'fail']);
        }
    }

    //求购留言列表 接口参数：id->求购编号 num->页码
    public function buyWordsList()
    {
        $res = $this->request->param();
        if (isset($res['id'])&&isset($res['num'])) {
            $id=$res['id'];
            $num=$res['num']==''?1:$res['num'];
            $data =Buy::get($id);
            if ($data!=null) {
                $list = Words::all(function ($query) use ($num,$id) {
                    $query
                        ->alias('w')
                        ->field('w.id,w.content,w.create_time')
                        ->join('mh_member m', 'm.id=w.mh_member_id')
                        ->field('m.nickname,m.headimg')
                        ->where('w.status', 1)
                        ->where('w.mh_buy_id', $id)
                        ->limit(5)
                        ->page($num)
                        ->order('w.create_time', 'desc');
                });
                //   $list1 = Db::name('buy')
                //   ->where('id','eq',$id)
                //   ->field('title,content,create_time')
                //   ->select();
                $list2 =Buy::get(function ($query) use ($id) {
                    $query->where('id', $id)
                        ->field('title,content,create_time');
                });
                return json([$list2,$list]);
            } else {
                return json(['code'=>400,'msg'=>'fail']);
            }
        } else {
            return json(['code'=>400,'msg'=>'fail']);
        }
    }

    //供货留言列表 接口参数：id->供货编号 num->页码
    public function sellWordsList()
    {
        $res = $this->request->param();
        if (isset($res['id'])&&isset($res['num'])) {
            $id=$res['id'];
            $num=$res['num']==''?1:$res['num'];
            $data =Sell::get($id);
            if ($data!=null) {
                $list = Words::all(function ($query) use ($num,$id) {
                    $query
                                ->alias('w')
                                ->field('w.id,w.content,w.create_time')
                                ->join('mh_member m', 'm.id=w.mh_member_id')
                                ->field('m.nickname,m.headimg')
                                ->where('w.status', 1)
                                ->where('w.mh_sell_id', $id)
                                ->limit(5)
                                ->page($num)
                                ->order('w.create_time', 'desc');
                });
                $list2 =Sell::get(function ($query) use ($id) {
                    $query->where('id', $id)
                                    ->field('title,content,create_time');
                });
                return json([$list2,$list]);
            } else {
                return json(['code'=>400,'msg'=>'fail']);
            }
        } else {
            return json(['code'=>400,'msg'=>'fail']);
        }
    }

    //会员的求购列表 id 会员编号  num 页码
    public function myBuyList()
    {
        if ($this->isLogin()) {
            $res = $this->request->param();
            //  if(isset($res['id'])&&isset($res['num'])){
            if (isset($res['num'])) {
                $id=Session::get('uuid')['id'];
                $num=$res['num']==''?1:$res['num'];
                $list = Buy::all(function ($query) use ($num,$id) {
                    $query->where('status', 1)
                                    ->where('mh_member_id', $id)
                                    ->limit(10)
                                    ->page($num)
                                    ->order('create_time', 'desc');
                });
                return json($list);
            } else {
                return json(['code'=>400,'msg'=>'fail']);
            }
        } else {
            return json(['code'=>400,'msg'=>'no login']);
        }
    }

    //会员的供货列表 id 会员编号  num 页码
    public function mySellList()
    {
        if ($this->isLogin()) {
            $res = $this->request->param();
            // if(isset($res['id'])&&isset($res['num'])){
            if (isset($res['num'])) {
                $id=Session::get('uuid')['id'];
                $num=$res['num']==''?1:$res['num'];
                $list = Sell::all(function ($query) use ($num,$id) {
                    $query->where('status', 1)
                                                ->where('mh_member_id', $id)
                                                ->limit(10)
                                                ->page($num)
                                                ->order('create_time', 'desc');
                });
                return json($list);
            } else {
                return json(['code'=>400,'msg'=>'fail']);
            }
        } else {
            return json(['code'=>400,'msg'=>'no login']);
        }
    }
    //求购编辑页渲染 id 求购id
    public function buyEdit()
    {
        if ($this->login()) {
            $data=$this->request->param();
            if (isset($data['id'])) {
                $list = Buy::get($data['id']);
                return json($list);
            } else {
                return json(['code'=>400,'msg'=>'fail']);
            }
        } else {
            return json(['code'=>400,'msg'=>'no login']);
        }
    }

    //求购列编辑
    public function doBuyEdit()
    {
        $data=$this->request->param();
        $user = new Buy;
        $res=$user->saveAll([$data]);
        if ($res!=false) {
            return json(['code'=>200,'msg'=>'update success']);
        } else {
            return json(['code'=>400,'msg'=>'update fail']);
        }
    }
    //供货编辑页渲染 id 供货id
    public function sellEdit()
    {
        if ($this->isLogin()) {
            $data=$this->request->param();
            if (isset($data['id'])) {
                $list = Sell::get($data['id']);
                return json($list);
            } else {
                return json(['code'=>400,'msg'=>'fail']);
            }
        } else {
            return json(['code'=>400,'msg'=>'no login']);
        }
    }
    

    //供货列编辑
    public function doSellEdit()
    {
        $data=$this->request->param();
        $user = new Sell;
        $res=$user->saveAll([$data]);
        if ($res!=false) {
            return json(['code'=>200,'msg'=>'update success']);
        } else {
            return json(['code'=>400,'msg'=>'update fail']);
        }
    }

    //执行添加（求购） mh_member_id 会员id
    public function doBuyAdd()
    {
        if ($this->isLogin()) {
            $data = $this->request->param();
            $data['mh_member_id'] =Session::get('uuid')['id'];
            $user = new Buy;
            $user->data($data);
            $res= $user->save();
            if ($res!=false) {
                return json(['code'=>200,'msg'=>'add success']);
            } else {
                return json(['code'=>400,'msg'=>'add fail']);
            }
        } else {
            return json(['code'=>400,'msg'=>'no login']);
        }
    }

    //执行添加（供货）mh_member_id 会员id
    public function doSellAdd()
    {
        if ($this->isLogin()) {
            $data = $this->request->param();
            $data['mh_member_id'] =Session::get('uuid')['id'];
            $user = new Sell;
            $user->data($data);
            $res= $user->save();
            if ($res!=false) {
                return json(['code'=>200,'msg'=>'update success']);
            } else {
                return json(['code'=>400,'msg'=>'update fail']);
            }
        } else {
            return json(['code'=>400,'msg'=>'no login']);
        }
    }
                               
    //会员全部留言列表  会员编号  id
    //    public function memberWordsList()
    //    {
    //      $res = $this->request->param();
    //      if(isset($res['id'])&&isset($res['num'])){
    //          $id=$res['id'];
    //          $num=$res['num']==''?1:$res['num'];
    //              $list = Words::all(function($query) use($num,$id){
    //                  $query->where('status', 1)
    //                  ->where('mh_member_id',$id)
    //                  ->limit(10)
    //                  ->page($num)
    //                  ->order('create_time', 'desc');
    //                  });
    //                  return json($list);
    //      }else{
    //          return json(['code'=>400,'msg'=>'fail']);
    //      }
             
    //    }
                               
                               
    //我的全部留言
    public function myAllWords()
    {
        if ($this->isLogin()) {
            $res = $this->request->param();
            //   if(isset($res['id'])&&isset($res['num'])){
            if (isset($res['num'])) {
                $id=Session::get('uuid')['id'];
                $num=$res['num']==''?1:$res['num'];
                $words= Db::view('mh_member', 'id as user_id, name as user_name')
                                        ->view('mh_buy', 'title,content,name as create_buy_name,create_time', 'mh_buy.mh_member_id=mh_member.id')
                                        ->view('mh_words', 'content as user_msg', 'mh_words.mh_buy_id=mh_buy.id')
                                        ->where('mh_member.id', 'eq', $id)
                                        ->page("$num,1")
                                        ->select();
                $words1= Db::view('mh_member', 'id as user_id, name as user_name')
                                         ->view('mh_sell', 'title,content,name as create_sell_name,create_time', 'mh_sell.mh_member_id=mh_member.id')
                                         ->view('mh_words', 'content as user_msg', 'mh_words.mh_sell_id=mh_sell.id')
                                         ->where('mh_member.id', 'eq', $id)
                                         ->page("$num,1")
                                         ->select();
    
                $arr3=json_encode($words, JSON_FORCE_OBJECT);
                $arr4=json_decode($arr3, true);//转为数组
    
                $arr5=json_encode($words1, JSON_FORCE_OBJECT);
                $arr6=json_decode($arr5, true);//转为数组
    
                $arr7=array_merge($arr4, $arr6);//合并
                return json_encode($arr7, JSON_FORCE_OBJECT) ;
            } else {
                return json(['code'=>400,'msg'=>'fail']);
            }
        } else {
            return json(['code'=>400,'msg'=>'no login']);
        }
    }

    //添加求购留言 参数 ： mh_member_id 会员编号  ；mh_buy_id 当前求购信息编号
    public function addBuyMsg()
    {
        $data = $this->request->param();
        // if(isset($data['mh_member_id'])){
        if ($this->isLogin()) {
            $data['mh_member_id']=Session::get('uuid')['id'];
            $word =new Words;
            $res = $word->saveAll([$data]);
            if ($res!=false) {
                return json(['code'=>200,'msg'=>'add success']);
            } else {
                return json(['code'=>400,'msg'=>'add fail']);
            }
        } else {
            return json(['code'=>400,'msg'=>'no login']);
        }
    }
                               
    //添加供货留言 参数 ： mh_member_id 会员编号  ；mh_buy_id 当前供货信息编号
    public function addSellMsg()
    {
        $data = $this->request->param();
        if ($this->isLogin()) {
            $data['mh_member_id']=Session::get('uuid')['id'];
            $word =new Words;
            $res = $word->saveAll([$data]);
            if ($res!=false) {
                return json(['code'=>200,'msg'=>'add success']);
            } else {
                return json(['code'=>400,'msg'=>'add fail']);
            }
        } else {
            return json(['code'=>400,'msg'=>'add fail']);
        }
    }
    //平台Faq
    public function faqShow()
    {
        $list= Faqtext::all()->toArray();
        return json($list);
    }
}
