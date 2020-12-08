<?php
namespace app\index\controller;
use app\common\controller\Base;
use app\index\model\Words;
use think\Db;
use think\Session;
class Words1 extends Base
{
    //留言列表
    public function wordsList()
    {
        $list = new Words;   
        $data= Db::name('Words')
        ->alias('w')
        ->field('w.id,w.content,w.status,FROM_UNIXTIME(w.create_time,"%Y-%m-%d %H:%i:%s") as create_time,w.name,w.phone,w.mh_member_id')
        ->join('mh_member m','m.id=w.mh_member_id')
        ->field('m.nickname,m.headimg')
        ->where('w.status','<',2)
        ->paginate(10); 
        $data1= Words::all()->toArray();    
        //dump($data);
        // 获取分页显示
        $page = $data->render();
        $this->assign('list',$data);
        $this->assign('page',$page);
        $this->assign('count',count($data1));
        return $this->view->fetch();

    }
    //留言启用、禁用
    public function setStatus()
    {
        $id = $this->request->param('id');
        $res = Words::get($id);
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
    //留言编辑
    public function wordsEdit()
    {
        $id = $this->request->param('id');
        $res = Words::get($id)->toArray();
        $this->assign('info',$res);
        return $this->view->fetch();


    }
    //执行更新
    public function doEdit()
    {

        $data = $this->request->param();
        $word =new Words;
        $res = $word->saveAll([$data]);
        if($res!=false){
             return ['msg'=>1];
        }else{
            return ['msg'=>0];

        }


    }
    //按时间搜索
    public function find()
    {
        $data = $this->request->param();
        $start='';
        $end ='';
        // $nickname ='';
        if(isset($data['start'])){
            Session::set('start',$data['start'].' 00:00:00');
            Session::set('end',$data['end'].' 23:59:59');
            // Session::set('nickname',$data['nickname']);
        }else{
           $start=Session::get('start');
           $end = Session::get('end'); 
           //$nickname = Session::get('nickname'); 
        }
        $start=$start==''?Session::get('start'):$start;
        $end = $end==''?Session::get('end'):$end;
        // $nickname = $nickname==''?Session::get('nickname'):$nickname;
       // $list = new Words;   
        $data= Db::name('Words')
        ->alias('w')
        ->field('w.id,w.content,w.status,FROM_UNIXTIME(w.create_time,"%Y-%m-%d %H:%i:%s") as create_time,w.name,w.phone,w.mh_member_id')
        ->join('mh_member m','m.id=w.mh_member_id')
        ->field('m.nickname,m.headimg')
        ->where('w.create_time','between time',[$start,$end])
        // ->whereOr('nickname','like',"%$nickname%")
        // ->where('create_time','between time',[$start,$end])
        // ->field('id,content,status,FROM_UNIXTIME(create_time,"%Y-%m-%d %H:%i:%s") as create_time,name,phone')
        ->paginate(10); 
         $data1=Db::name('Words')//统计数量
        ->where('create_time','between time',[$start,$end])
        ->select();    
        // 获取分页显示
        $page = $data->render();
        $this->assign('list',$data);
        $this->assign('page',$page);
        $this->assign('count',count($data1));
        return $this->view->fetch('words1/words_list');

    }

}