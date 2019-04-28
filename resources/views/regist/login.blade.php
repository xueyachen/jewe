<p>用户名：<input type="text" name="user_tel"></p>
<p>密码：<input type="text" name="user_pwd"></p>
<p><input type="button" value="登录" class="but"></p>
<script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
<script>
    $(function(){
        //登录
        $(document).on('click','.but',function(){
            var tel=$("input[name='user_tel']").val();
            var pwd=$("input[name='user_pwd']").val();
            var regTel=/^\d{11}$/;//电话正则
            var regPwd=/^\w{6,8}$/;//密码正则
            var _this=$(this);
            if(tel==''){
                alert('用户名不能为空');
                return false;
            }else if(!regTel.test(tel)){
                alert('手机号是由11位的数字组成');
                return false;
            }else if(pwd==''){
                alert('密码不能为空');
                return false;
            }else if(!regPwd.test(pwd)){
                alert('密码支持6位到8位');
                return false;
            }else{
                _this.next().remove();
                $.post(
                        "/index/loginSub",
                        {tel:tel,pwd:pwd},
                        function(res){
                            _this.next().remove();
                            if(res.code==1){
                                _this.after("<center>"+res.font+"</center>");
                            }else if(res.code==2){
                                _this.next().remove();
                                _this.after("<center>"+res.font+"</center>");
                            }else if(res.code==3){
                                _this.after("<center>"+res.font+"</center>");
                            }else if(res.code==4){
                                _this.after("<center>"+res.font+"</center>");
                            }else if(res.code==5){
                                _this.after("<center>"+res.font+"</center>");
                            }else if(res.code==6){
                                location.href='/index/lists';
                                _this.after("<center>"+res.font+"</center>");
                            }
                        }
                        ,'json'
                )
            }
        })
    })
</script>