<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Author" contect="http://www.webqin.net">
    <title>三级分销</title>
    <link rel="shortcut icon" href="/jewell/images/favicon.ico" />

    <!-- Bootstrap -->
    <link href="/jewell/css/bootstrap.min.css" rel="stylesheet">
    <link href="/jewell/css/style.css" rel="stylesheet">
    <link href="/jewell/css/response.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="http://cdn.bootcss.com/respond./jewell/js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="maincont">
    <header>
        <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
        <div class="head-mid">
            <h1>收货地址</h1>
        </div>
    </header>
    <div class="head-top">
        <img src="/jewell/images/head.jpg" />
    </div><!--head-top/-->

    <div class="lrBox">
        <input type="hidden" name="address_id" value="{{$result->address_id}}" id="hidden">
        <div class="lrList"><input type="text" placeholder="收货人" name="address_name" class="name" value="{{$result->address_name}}"/></div>


        <div class="lrList">
            <select name="province" class="change" id="province">
                <option value="q">请选择省份</option>
                @foreach($provinceInfo as $k=>$v)
                    @if($v->id==$result->province)
                        <option value="{{$v->id}}" selected>{{$v->name}}</option>
                    @else
                        <option value="{{$v->id}}">{{$v->name}}</option>
                    @endif
                @endforeach
            </select>



            <select name="city" class="change" id="city">
                @foreach($cityInfo as $k=>$v)
                    @if($v->id==$result->city)
                        <option value="{{$v->id}}" selected>{{$v->name}}</option>
                    @else
                        <option value="{{$v->id}}">{{$v->name}}</option>
                    @endif
                @endforeach
            </select>



            <select name="area" class="change" id="area">
                @foreach($areaInfo as $k=>$v)
                    @if($v->id==$result->city)
                        <option value="{{$v->id}}" selected>{{$v->name}}</option>
                    @else
                        <option value="{{$v->id}}">{{$v->name}}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="lrList"><input type="text" placeholder="详细地址" name="address_detail" class="detail" value="{{$result->address_detail}}"/></div>
        <div class="lrList"><input type="text" placeholder="手机" name="address_tel" class="tel" value="{{$result->address_tel}}"/></div>
        <div class="lrList2">
            设为默认
            @if($result->is_default==1)
                <input type="checkbox" name="is_default" checked>
            @else
                <input type="checkbox" name="is_default">
            @endif
        </div>
    </div><!--lrBox/-->
    <div class="lrSub">
        <input type="submit" value="修改" class="sub"/>
    </div>


    <div class="height1"></div>
    @include('public.footer')
</div><!--maincont-->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="/jewell/js/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/jewell/js/bootstrap.min.js"></script>
<script src="/jewell/js/style.js"></script>
<!--jq加减-->
<script src="/jewell/js/jquery.spinner.js"></script>
<script>
    $('.spinnerExample').spinner({});
</script>
</body>
</html>

<script>
    $(function(){
        //内容更新事件
        $(document).on('change','.change',function(){
            //获取当前点击的省份的id
            var _this=$(this);
            var id=_this.val();
            var _option="<option value=''>--请选择--</option>";
            _this.nextAll('select').html(_option);
            //将 省份id 传给控制器去查询市区信息
            $.post(
                    "/index/change",
                    {id:id},
                    function(res){
                        for(var i in res){
                            _option+="<option value='"+res[i]['id']+"'>"+res[i]['name']+"</option>";
                            _this.next('select').html(_option);
                        }
                    }
            )
        })
        //添加 收货地址
        $(document).on('click','.sub',function(){
            var obj={};
            obj.address_name=$('.name').val();
            obj.address_detail=$('.detail').val();
            //省
            obj.province=$('#province').val();
            //区
            obj.city=$('#city').val();
            //县
            obj.area=$('#area').val();
            obj.address_id=$('#hidden').val();
            obj.address_tel=$('.tel').val();
            obj.is_default=$("input[name='is_default']").prop('checked');
            if(obj.is_default==true){
                obj.is_default=1;//默认
            }else{
                obj.is_default=2//未默认
            }
            //验证
            if(obj.address_name==''){
                alert('收货人名字不能为空');
                return false;
            }
            if(obj.province=='q'){
                alert('请选择省份信息');
                return false;
            }
            if(obj.city=='0'){
                alert('请选择直辖市信息');
                return false;
            }
            if(obj.area==''){
                alert('请选择县区信息');
                return false;
            }
            if(obj.address_detail==''){
                alert('详细地址不能为空');
                return false;
            }
            var reg=/^0?(13|14|15|18)[0-9]{9}$/;
            if(obj.address_tel==''){
                alert('电话不能为空');
                return false;
            }else if(!reg.test(obj.address_tel)){
                alert('手机号支持13,14,15,18开头的11位数字');
                return false;
            }

            $.post(
                    "/index/addressupdate",
                    obj,
                    function(res){
                        if(res==1){
                            alert('修改收货地址成功');
                            location.href='/index/addresslist';
                        }else{
                            alert("修改收货地址失败");
                            location.href='/index/addresslist';
                        }

                    }
            )
        })
    })
</script>