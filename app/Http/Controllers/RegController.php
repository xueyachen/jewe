<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use DB;
class RegController extends Controller
{
    
    public function index(){
    	echo 1;
    }
    //短信发送
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
	//获取手机号验证码
	public function tel(){
	    $email=Request()->email;
	    $rand=rand(100000,999999);
	    $where1=[
    		'user_tel'=>$email
    	];
    	$count=DB::table('userr')->where($where1)->count();
    	if($count==0){
    		$send=$this->send($email,$rand);
    	}else{
    		return ['code'=>'2','msg'=>'手机号已注册'];
    	}  
	    $arr=[
	    	'tel'=>$email,
	    	'time'=>time(),
	    	'rand'=>$rand
	    ];
	    if($send==00000){
	    	Request()->session()->put('tel', $arr);
	    	return ['code'=>'1','msg'=>'发送成功'];
	    	
	    }else{
	    	return ['code'=>'2','msg'=>'发送失败'];
	    }  	
	}
	//获取邮箱的验证码
	public function email(){
       //接收数据
        $email=Request()->email;
        $rand=rand(100000,999999);
        $where1=[
    		'user_email'=>$email
    	];
    	$count=DB::table('userr')->where($where1)->count();
    	if($count==0){
    		if($email){
	            $res=Mail::send('login/lists',['content'=>$rand],function($message)use($email){
	                    $message->subject('欢迎注册微商城有限公司');
	                    $res=$message->to($email);
	            });
	           
        	}
    	}else{
    		return ['msg'=>'邮箱已存在','code'=>2];
    	}
    	$arr=[
		            'time'=>time(),
		            'email'=>$email,
		            'rand'=>$rand
	            ]; 
	            if(!$res){
	            	 Request()->session()->put('email', $arr);//再将值存入session中
	                return ['msg'=>'发送成功','code'=>1];

	            }else{
	                return ['msg'=>'发送失败','code'=>2];
	            }
        
    }
    //手机号验证唯一
    public function checktel(){
    	$email=Request()->email;
    	$count=DB::table('userr')->where('user_tel',$email)->count();
    	if($count>0){
    		echo 2;
    	}else{
    		echo 1;
    	}
    }
    //邮箱验证唯一
    public function checkemaill(){
    	$email=Request()->email;
    	$count=DB::table('userr')->where('user_email',$email)->count();
    	if($count>0){
    		echo 2;
    	}else{
    		echo 1;
    	}
    }


    //注册入库
    public function store(){
    	$data=Request()->all();
    	//判断邮箱 或者 手机号
    	if($data['type']==1){
    		//走手机号
    		$arr = Request()->session()->get('tel');//获取存入session中的验证码
    		if($arr['tel']==$data['email']&&$arr['rand']==$data['code']){
    			$where=[
    				'user_tel'=>$data['email'],
    				'user_pwd'=>$data['pwd'],
    				'user_code'=>$data['code'],
    				'create_time'=>time()
    			];
    			$where1=[
    				'user_tel'=>$data['email']
    			];
    			$count=DB::table('userr')->where($where1)->count();
    			if($count==0){
    				$res=DB::table('userr')->insert($where);
    				if($res){
    					return ['code'=>1,'msg'=>'手机号注册成功'];
    					Request()->session()->forget('tel');//删除session中的值
    				}else{
    					return ['code'=>2,'msg'=>'手机号注册失败'];
    				}
    			}else{
    				return ['code'=>2,'msg'=>'手机号已存在'];
    			}
    		}else{
				return ['code'=>3,'msg'=>'验证码或者手机号有误'];
			}
    	}else{
    		//走邮箱
    		$arr = Request()->session()->get('email');//获取存入session中的验证码
    		if($arr['email']==$data['email']&&$arr['rand']==$data['code']){
    			$where=[
    				'user_email'=>$data['email'],
    				'user_pwd'=>$data['pwd'],
    				'user_code'=>$data['code'],
    				'create_time'=>time()
    			];
    			$where1=[
    				'user_email'=>$data['email']
    			];
    			$count=DB::table('userr')->where($where1)->count();
    			if($count==0){
    				$res=DB::table('userr')->insert($where);
    				if($res){
						return ['code'=>'1','msg'=>'邮箱注册成功'];
						Request()->session()->forget('email');//删除session中的值
    				}else{
    					return ['code'=>'2','msg'=>'邮箱注册失败'];
    				}
    			}else{
    				return ['code'=>'2','msg'=>'邮箱已存在'];
    			}
    		}else{
    			return ['code'=>'2','msg'=>'邮箱或验证码有误'];
    		}
    		
    	}
    }
	
}
