<?php
/**
 * 基础控制器
 * 必须继承自think\Controller.php
 */

namespace app\common\controller;
use think\Controller;
use think\Session;
class Base extends Controller
{
     // 初始化

     protected function initialize()
     {
  
     }
     //检查是否登录，放在首页入口
     public function isLogin()
     {

         if(!Session::has('info','think')) {
           $this->error('用户尚未登录,无权访问！','index/login/login');
         }

     }
     //防止用户重复登录，放在登录入口
     public function alreadyLogin()
     {
          if(Session::has('info','think')) {
               $this->error('您已登录,请勿重复登录！','index/index/index');
             }
     }

     //curl  post请求
     public function curlPost($url,$post)
     {
   
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容aa
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $res = curl_exec($curl); // 执行操作
        curl_close($curl);
        $j = json_decode($res, true);
        return $j;

     }
     //curl  post请求
     public function curl($url,$post)
     {
   
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容aa
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $res = curl_exec($curl); // 执行操作
        curl_close($curl);
        return $res;

     }
   //验证是否包含
    public  function checkstr($str,$needle){
      $needle =$needle;//判断是否包含a这个字符
      $tmparray = explode($needle,$str);
      if(count($tmparray)>1){
      return true;
      } else{
      return false;
      }
     }

}
