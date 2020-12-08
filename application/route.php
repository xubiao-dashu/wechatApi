<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;
Route::rule('test','index/V1/test');//图片上传接口
Route::rule('upload','index/V1/upload');//图片上传接口
Route::rule('lunlist','index/V1/lunList');//轮播图路由
Route::rule('buylist','index/V1/buyList');//最新求购路由
Route::rule('selllist','index/V1/sellList');//最新求购路由
Route::rule('findbuy','index/V1/findBuy');//求购查询路由
Route::rule('findsell','index/V1/findSell');//供货查询路由
Route::rule('info','index/V1/memberInfo');//会员信息
Route::rule('buyfen','index/V1/buyListFen');//求购列表
Route::rule('sellfen','index/V1/sellListFen');//供货列表
Route::rule('buywords','index/V1/buyWordsList');//求购留言列表
Route::rule('sellwords','index/V1/sellWordsList');//供货留言列表
Route::rule('memberwords','index/V1/memberWordsList');//会员留言列表(不含上一级)
Route::rule('mybuylist','index/V1/myBuyList');//会员求购列表
Route::rule('myselllist','index/V1/mySellList');//会员供货列表
Route::rule('editbuy','index/V1/doBuyEdit');//我的求购编辑
Route::rule('ebuy','index/V1/buyEdit');//我的求购编辑页渲染
Route::rule('editSell','index/V1/doSellEdit');//我的供货编辑
Route::rule('esell','index/V1/sellEdit');//我的求购编辑页渲染
Route::rule('addbuy','index/V1/doBuyAdd');//我的求购添加
Route::rule('addSell','index/V1/doSellAdd');//我的供货添加
Route::rule('my','index/V1/myAllWords');//我的留言列表（含上一级） 
Route::rule('addbuymsg','index/V1/addBuyMsg');//添加求购留言 
Route::rule('addsellmsg','index/V1/addSellMsg');//添加供货留言 
Route::rule('faqshow','index/V1/faqShow');//添加供货留言 
Route::rule('reg','index/V1/register');//会员注册
Route::rule('login','index/V1/login');//会员登录

/** 小程序接口 */
Route::rule('voicelist','index/V1/voiceList');//音频列表
return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];
