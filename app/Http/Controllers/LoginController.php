<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Cookie;
class LoginController extends Controller
{
    public function index(){

    }
    //登录
    public function subtel(){
        $email=Request()->email;
        $pwd=Request()->pwd;
        $where1=[
            ['user_tel','=',$email]
        ];
        $where2=[
            ['user_email','=',$email]
        ];
        $res=DB::table('userr')->where($where1)->orwhere($where2)->first();


        if(!$res){
            echo 3;
        }else{
            if($pwd!=$res->user_pwd){
                echo 2;
            }else{
                echo 1;
                //将用户id 邮箱存入数组中
                $arr=[
                    'user_id'=>$res->user_id,
                    'user_email'=>$res->user_email
                ];
                //登录成功 将用户ID 和 邮箱存入session
                Request()->session()->put('user', $arr);
                //登录成功 清楚cookie中购物车的数据
                Cookie::queue(Cookie::forget('car'));
            }
        }
    }
}