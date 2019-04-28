<p>用户:<input type="text" name="user_tel"></p>
<p>验证码:<input type="text" name="user_code"><button class="h">获取</button></p>
<p>密码:<input type="password" name="user_pwd"></p>
<input type="button" value="注册" class="but">
<script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
<script>
    $(function(){
        //点击获取
        $(document).on('click','.h',function(){
            var email=$("input[name='user_tel']").val();
            var reg=/^\d{11}$/;
            var falg=false;
            if(email==''){
                alert('手机号不能为空');
                return false;
            }else if(!reg.test(email)){
                alert('手机号允许11位数字');
                return false;
            }else{
                //手机号唯一
                $.ajax({
                    method: "POST",
                    url: "index/regist",
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
                        "index/sendTel",
                        {email:email},
                        function(res){
//                            console.log(res);
                            if(res==2){
                                alert('发送失败');
                                return false;
                            }else{
                                alert('发送成功');
                                return true;
                            }
                        }
                )
            }
        })
        //注册
        $(document).on('click','.but',function(){
            var email=$("input[name='user_tel']").val();//电话
            var code=$("input[name='user_code']").val();//电话//验证码
            var pwd=$("input[name='user_pwd']").val();//密码

            var regTel=/^\d{11}$/;//电话正则
            var regPwd=/^\w{6,8}$/;//密码正则
            if(email==''){
                alert('手机号不能为空');
                return false;
            }else if(!regTel.test(email)){
                alert('手机号允许11位数字');
                return false;
            }else if(code==''){
                alert('验证码不能为空');
                return false;
            }else if(pwd==''){
                alert('密码不能为空');
                return false;
            }else if(!regPwd.test(pwd)){
                alert('密码允许6位到8位数字');
                return false;
            }else{
                //手机号唯一
                $.ajax({
                    method: "POST",
                    url: "index/regist",
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
            }
            $.post(
                    "index/registSub",
                    {email:email,code:code,pwd:pwd},
                    function(res){
                        if(res==1){
                            alert('注册成功');
                            location.href='login1';
                        }else if(res==2){
                            alert('注册失败');
                        }else if(res==3){
                            alert('邮箱或者验证码有误');
                        }
                    }
            )
        })
    })
</script>