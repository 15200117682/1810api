<?php

namespace App\Http\Controllers\User;

use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;



class UserController extends Controller
{
    //测试数据库和redis是否链接成功
    public function login(){
        $key="user";
        Redis::set($key,"zhangsan");
        $one=Redis::get($key);
        var_dump($one);exit;
        $arr=[
            'user_name'=>"xiaocong",
            'user_pwd'=>"123123"
        ];
        $res=UserModel::insertGetId($arr);
        var_dump($res);exit;
    }
}
