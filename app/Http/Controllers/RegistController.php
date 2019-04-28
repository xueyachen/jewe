<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class RegistController extends Controller
{
    //手机号验证唯一
    public function index(){
        $email=Request()->email;
        $count=DB::table('userr')->where('user_tel',$email)->count();
        if($count==0){
            //注册
            echo 1;
        }else{
            //已注册
            echo 2;
        }
    }
    //获取验证码
    public function sendTel(){
        $email=Request()->email;
        //生成随机6位验证码
        $rand=rand(100000,999999);
        $telsend=$this->send($email,$rand);
        if($telsend==00000){
            echo 1;
            $arr=[
                'rand'=>$rand,
                'tel'=>$email,
                'time'=>time()
            ];
            Request()->session()->put('tel', $arr);
        }else{
            echo 2;
        }
    }
    //注册提交
    public function add(){
        $email=Request()->email;
        $pwd=Request()->pwd;
        $code=Request()->code;
        //获取session中的验证码
        $rand=Request()->session()->get('tel.rand');
        if($rand==$code){
            //走注册
            $arr=[
                'user_tel'=>$email,
                'user_pwd'=>$pwd,
                'user_code'=>$code
            ];
            $res=DB::table('userr')->insert($arr);
            if($res){
                echo 1;
                Request()->session()->forget('tel');//删除session中的值
            }else{
                echo 2;
            }
        }else{
            echo 3;
        }
    }
    //登录提交
    public function loginList(){
        $data=Request()->all();
        $count=DB::table('userr')->where('user_tel',$data['tel'])->first();
        $count=json_encode($count);
        $count=json_decode($count,true);

        $time=time();//当前时间
        $last_error_time=$count['last_error_time'];//最后一次错误时间
        $error_num=$count['error_num'];//错误次数
        $user_id=$count['user_id'];//用户ID

        if(!$count){
            return ['code'=>1,'font'=>"您账号未注册请先注册"];
        }else{
            //判断密码是否相等
            if($data['pwd']==$count['user_pwd']){
                //密码正确
                if($error_num>=5&&$time-$last_error_time<3600){
                    $fen=60-ceil(($time-$last_error_time)/60);
                    return ['code'=>5,'font'=>"您账号已锁定，还有'$fen'分钟后才能登录"];
                }else{
                    $where1=[
                        'error_num'=>0,
                        'last_error_time'=>time()
                    ];
                    $res=DB::table('userr')->where('user_id',$user_id)->update($where1);
                    $arr=[
                        'user_tel'=>$count['user_tel'],
                        'user_id'=>$user_id
                    ];
                    //登陆成功 将 电话 和 验证码存入 session中
                    Request()->session()->put('arr',$arr);
                    return ['code'=>6,'font'=>"登陆成功"];
                }
            }else{
                //密码错误
                if($time-$last_error_time>3600){
                    $whereupdate=[
                        'error_num'=>1,
                        'last_error_time'=>$time
                    ];
                    $res=DB::table('userr')->where('user_id',$user_id)->update($whereupdate);

                    return ['code'=>2,'font'=>'您的密码有误，还有4次机会可以登录'];
                }else{
                    if($error_num>=5){
                        $fen=60-ceil(($time-$last_error_time)/60);
                        return ['code'=>3,'font'=>"您的账号已锁定,您还有'.$fen.'分钟可以登录"];
                    }else{
                        $num=$error_num+1;
                        $whereupdate1=[
                            'error_num'=>$num,
                            'last_error_time'=>time()
                        ];
                        $res=DB::table('userr')->where('user_id',$user_id)->update($whereupdate1);
                        $num=5-($error_num+1);
                        return ['code'=>2,'font'=>'您的密码有误，还有'.$num.'次机会可以登录'];
                    }
                }
            }
        }
    }

    public function lists(){
        $user_tel=Request()->session()->get('arr.user_tel');
        $res=cache('res'.$user_tel);
        if(!$res){
            $res= DB::table('userr')->where('user_tel',$user_tel)->first();
            cache(['res'.$user_tel=>$res],60*24);
        }
        $res=json_encode($res);
        $res=json_decode($res,true);
        return view('regist.admin',compact('res'));
    }

    //发送短信
    public function send($email,$rand){
        $host = "http://dingxin.market.alicloudapi.com";
        $path = "/dx/sendSms";
        $method = "POST";
        $appcode = "81f4c93dd39c45ada67a72f93e01a57e";
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);

        $querys = "mobile=".$email."&param=code%3A".$rand."&tpl_id=TP1711063";
        $bodys = "";
        $url = $host . $path . "?" . $querys;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $curl=json_decode(curl_exec($curl),true);
        return $curl['return_code'];
    }



}
