@extends('layouts.shop')
@section('title','微商城登录')
@section('content')
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>会员注册</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="/jewell/images/head.jpg" />
     </div><!--head-top/-->

      <h3>还没有三级分销账号？点此<a class="orange" href="/reg">注册</a></h3>
      <div class="lrBox">
       <div class="lrList"><input type="text" placeholder="输入手机号码或者邮箱号"class="email"/></div>
       <div class="lrList"><input type="text" placeholder="输入密码" class="pwd"/></div>
          <input type="hidden" name="type">
      </div><!--lrBox/-->
      <div class="lrSub">
       <input type="submit" value="立即登录" class="sub"/>
      </div>
     <script>
         $(function(){
             //登录
             $('.sub').click(function(){
                 var email=$('.email').val();
                 var pwd=$('.pwd').val();
                 var tel=/^\d{11}$/;
                 var tell=/^\w+@\w+\.com$/;
                 var pwdreg=/^\w{6,8}$/;//密码正则

                 if(email=='') {
                     alert('手机号码或邮箱不能为空');
                     return false;
                 }else if(pwd==''){
                     alert('密码不能为空');
                     return false;
                 }else if(!pwdreg.test(pwd)){
                     alert('密码格式不正确');
                     return false;
                 }
//                 if(tell.test(email)){
//                     if(!tell.test(email)){
//                         alert('邮箱格式不正确');
//                         return false;
//                     }
//                 }else if(tel.test(email)) {
//                     if(!tel.test(email)){
//                         alert('手机号码格式不正确');
//                         return false;
//                     }
//                 }

                     //走手机
                     $.post(
                             "/index/subtel",
                             {email:email,pwd:pwd},
                             function(res){
//                                 console.log(res);
                                 if(res==1){
                                     alert('登陆成功');
                                     location.href='/index';
                                 }else if(res==3){
                                     alert('您的手机号未注册，请先注册');
                                     location.href='/reg';
                                 }else if(res==2){
                                     alert('您的账号号或者密码错误');
                                 }
                             }
                     )










             })
         })
     </script>






     @include('public.footer')
     @endsection
   
   
