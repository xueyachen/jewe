@extends('layouts.shop')
@section('title','微商城注册')
@section('content')
      
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>会员注册</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="/jewell/images/head.jpg" />
     </div><!--head-top/-->
     
      <h3>已经有账号了？点此<a class="orange" href="/login">登陆</a></h3>
      <div class="lrBox">
       <div class="lrList"><input type="text" placeholder="输入手机号码或者邮箱号" name="user_email"/></div>
       <div class="lrList2"><input type="text" placeholder="输入短信验证码" class="code"/> <button class='but'>获取验证码</button></div>
       <div class="lrList"><input type="text" placeholder="设置新密码（6-18位数字或字母）" class="pwd"/></div>
       <div class="lrList"><input type="text" placeholder="再次输入密码" class="pwdd"/></div>
       <input type="hidden" name="type">
      </div><!--lrBox/-->
      <div class="lrSub">
       <input type="submit" value="立即注册" class="sub"/>
      </div>
     


     <script type="text/javascript">
          $(function(){
            //点击获取
            $('.but').click(function(){
              var _this=$(this);
              var email=_this.parent('div').prev('div').find('input').val();
              var tel=/^\d{11}$/; 
              {{--var tell=/^\d+@\w+\.com$/;--}}
              var tell=/^[A-Za-z\d]+([-_.][A-Za-z\d]+)*@([A-Za-z\d]+[-.])+[A-Za-z\d]{2,4}$/;
              _this.parent('div').next('span').remove();
              if(tel.test(email)){
                  //手机号唯一

                  $.ajax({
                      method: "POST",
                      url: "/index/checktel",
                      data: {email:email},
                      async:false
                  }).done(function(msg) {
                      if(msg==2){
                          alert('手机号已存在');
                          falg=false;
                      }else{
                          falg=true;
                      }
                  });
                  if(falg==false){
                      return falg;
                  }
                  //获取验证码
                  $.post(
                    "/index/tel",
                    {email:email},
                    function(res){
                        if(res.code==1){
                          _this.parent('div').after("<span style='color:red' id='span'>"+res.msg+"</span>");
                          $("input[name='type']").val(1);
                        }else{
                          _this.parent('div').after("<span style='color:red' id='span'>"+res.msg+"</span>");
                        }
                    }
                  )
              }else if(email==''){
                  alert('手机号或者邮箱不能为空');
                  return false;
              }else if(tell.test(email)){

                  //邮箱唯一
                  $.ajax({
                      method: "POST",
                      url: "/index/checkemaill",
                      data: {email:email},
                      async:false
                  }).done(function(msg) {
                      if(msg==2){
                          alert('邮箱已存在');
                          falg=false;
                      }else{
                          falg=true;
                      }
                  });
                  if(falg==false){
                      return falg;
                  }



                  $.post(
                      "/index/email",
                      {email:email},
                      function(res){
                        if(res.code==1){
                          _this.parent('div').after("<span style='color:red' id='span'>"+res.msg+"</span>");
                          $("input[name='type']").val(2);
                        }else{
                          _this.parent('div').after("<span style='color:red' id='span'>"+res.msg+"</span>");
                        }
                      }
                  )
              }
            })
            //注册
            $('.sub').click(function(){
              var type=$("input[name='type']").val();
              var email=$("input[name='user_email']").val();
              var code=$('.code').val();
              var pwd=$('.pwd').val();
              var pwdd=$('.pwdd').val();
              var pwdreg=/^\w{6,8}$/;//密码正则
              var telreg=/^\d{11}$/; //电话正则
              var emailreg=/^[A-Za-z\d]+([-_.][A-Za-z\d]+)*@([A-Za-z\d]+[-.])+[A-Za-z\d]{2,4}$/;//邮箱正则
              var falg=false;
              if(email==''){
                  alert('手机号或者邮箱不能为空');
                  falg=false;
              }else{
                  if(type==1){
                      if(!telreg.test(email)){
                          alert('手机号格式不正确');
                          falg=false;
                      }else{

                        //手机号唯一
                        $.ajax({
                            method: "POST",
                            url: "/index/checktel",
                            data: {email:email},
                            async:false
                          }).done(function(msg) {
                              if(msg==2){
                                  alert('手机号已存在');
                                  falg=false;
                              }else{
                                  falg=true;
                              }
                          });
                      }
                      if(falg==false){
                        return falg;
                      }
                  }else{
                      if(!emailreg.test(email)){
                          alert('邮箱格式不正确');
                          return false;
                      }else{
                          //邮箱唯一
                          $.ajax({
                              method: "POST",
                              url: "/index/checkemaill",
                              data: {email:email},
                              async:false
                            }).done(function(msg) {
                                if(msg==2){
                                    alert('邮箱已存在');
                                    falg=false;
                                }else{
                                    falg=true;
                                }
                            });
                            if(falg==false){
                                return falg;
                            }
                      }
                  }
              }
              if(code==''){
                alert('验证码不能为空');
                return false;
              }
              if(pwd==''){
                alert('密码不能为空');
                return false;
              }else if(!pwdreg.test(pwd)){
                alert('密码是由数字、字母、下划线组成6位到8位');
                return false;
              }

              if(pwdd==''){
                alert('确认密码不能为空');
                return false;
              }else if(pwd!=pwdd){
                alert('确认密码和密码不一致');
                return false;
              }
              $.post(
                    "/index/regist",
                    {type:type,email:email,code:code,pwd:pwd},
                    function(res){
                      if(res.code==1){
                          location.href='/login';
                          alert(res.msg);
                      }else{
                          alert(res.msg);  
                      }
                      // console.log(res);
                    }
                )
              



            })







          })
     </script>
      @endsection
      
  

   





    
