<?php
namespace app\index\controller;
use app\common\controller\Base;
use app\index\model\Lunbo;
use think\Db;
use think\Session;
class Lun extends Base
{
    //轮播列表
    public function lunboList()
    {
      // $data=Lunbo::all()->order()->toArray();
        $data = Lunbo::all(function($query){
        $query->where('status','egt', 0)->order(['sort'=>'desc']);
        })->toArray();  
        //dump($data);
       $this->assign('count',count($data));
       $this->assign('list',$data);
       return $this->view->fetch();

    }
    //轮播图添加页
    public function lunboAdd()
    {
        return $this->view->fetch();
    }
    //执行添加
    public function doAdd()
    {
        $data = $this->request->param();
        $lun=Lunbo::create([
            'title'=>$data['title'],
            'imgurl'=>$data['imgurl'],
            'content'=>isset($data['content'])?$data['content']:'',
        ]);
        if(isset($lun->id)){
        $lun1=Lunbo::get($lun->id);
        $lun1->sort=$lun->id;
        $res= $lun1->save();
        if($res!=false){
            return ['msg'=>'1'];
        }else{
            return ['msg'=>'0'];
        }
        
        }else{
            return ['0'];
        }

    }
    //编辑页
    public function lunboEdit()
    {
        $data = $this->request->param();
        $info =Lunbo::get($data['id'])->toArray();
        $this->assign('info',$info);
        return $this->view->fetch();

    }
     //执行更新
     public function doEdit()
     {
        $data = $this->request->param();
        $lun = Lunbo::update(
            ['id'=>$data['id'],
            'title'=>$data['title'],
            'content'=>$data['content'],
            'imgurl'=>$data['imgurl']]
        ); 
         if(isset($lun->id)){
             return ['msg'=>'1'];
         }else{
             return ['msg'=>'0'];
         }
         
 
     }
     //设置轮播图的状态
     public function setStatus()
     {
         $data = $this->request->param();
         $lun = Lunbo::get($data['id']);
         if($lun->status=='已发布'){//上架改为下架
            $lun1 = Lunbo::update(
                ['id'=>$data['id'],
                'status'=>0,]
            ); 
            if($lun1->status=='已下架'){
                return ['msg'=>1];
            }else{
                return ['msg'=>0];
            }

         }else{
            $lun1 = Lunbo::update(
                ['id'=>$data['id'],
                'status'=>1,]
            ); 
            if($lun1->status=='已发布'){
                return ['msg'=>1];
            }else{
                return ['msg'=>0];
            }
         }
     }
     //上移图片
     public function up()
     {
        $id= $this->request->param('id'); //当前id
        $sort1=db('lunbo')->where('id','eq',$id)->find()['sort'];//当前记录
        $list=db('lunbo')
     //   ->where('sort','<',$sort1)
        ->where('sort','>',$sort1)
        ->order(['sort'=>'asc'])
        ->limit(1)->select();
        if(count($list)>0){
            $sort=$list[0]['sort'];//上一条记录
            $bid=$list[0]['id'];//上一条记录 
            db('lunbo')->where('id',$id)->setField('sort',$sort);
            db('lunbo')->where('id',$bid)->setField('sort',$sort1);
            return ['msg'=>1];
        }else{
            return ['msg'=>0];
        }
       
     }
}