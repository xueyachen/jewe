@extends('layouts.shop')
@section('title','微商城首页')
@section('content')
     <div class="head-top">
      <img src="/jewell/images/head.jpg" />
      <dl>
       <dt><a href="user.html"><img src="/jewell/images/touxiang.jpg" /></a></dt>
       <dd>
        <h1 class="username">三级分销终身荣誉会员</h1>
        <ul>
         <li><a href="/index/goodslist"><strong>34</strong><p>全部商品</p></a></li>
         <li><a href="javascript:;"><span class="glyphicon glyphicon-star-empty"></span><p>收藏本店</p></a></li>
         <li style="background:none;"><a href="javascript:;"><span class="glyphicon glyphicon-picture"></span><p>二维码</p></a></li>
         <div class="clearfix"></div>
        </ul>
       </dd>
       <div class="clearfix"></div>
      </dl>
     </div>

     <form action="/index/goodslist" method="post" class="search">
          <input type="text" class="seaText fl" name="username"/>
          <input type="submit" value="搜索" class="seaSub fr" />
     </form>
     @if($user =='')
         <ul class="reg-login-click">
          <li><a href="login">登录</a></li>
          <li><a href="reg" class="rlbg">注册</a></li>
          <div class="clearfix"></div>
         </ul>
     @else
         <center>欢迎<font style="color:red">{{$user['user_email']}}</font>登录</center>
     @endif


     <div id="sliderA" class="slider">
      <img src="/jewell/images/image1.jpg" />
      <img src="/jewell/images/image2.jpg" />
      <img src="/jewell/images/image3.jpg" />
      <img src="/jewell/images/image4.jpg" />
      <img src="/jewell/images/image5.jpg" />
     </div><!--sliderA/-->
     <ul class="pronav">
         @foreach($topCateInfo as $k=>$v)
            <li><a href="/index/goodslist/{{$v->cate_id}}">{{$v->cate_name}}</a></li>
         @endforeach
      <div class="clearfix"></div>
     </ul><!--pronav/-->
     <div class="index-pro1">
         @foreach($new as $k=>$v)
              <div class="index-pro1-list">
                   <dl>
                        <dt><a href="/index/goodspart/{{$v->goods_id}}"><img src="http://juploads.com/imgUpload/{{$v->goods_img}}" width="300" height="200"/></a></dt>
                        <dd class="ip-text"><a href="/index/goodspart/{{$v->goods_id}}">{{$v->goods_name}}</a><span>库存：{{$v->goods_num}}</span></dd>
                        <dd class="ip-price"><strong>¥{{$v->goods_price}}</strong> <span>¥{{$v->market_price}}</span></dd>
                   </dl>
              </div>
         @endforeach
      <div class="clearfix"></div>
     </div><!--index-pro1/-->
     @foreach($best as $k=>$v)
         <div class="prolist">
          <dl>
           <dt><a href="/index/goodspart/{{$v->goods_id}}"><img src="http://juploads.com/imgUpload/{{$v->goods_img}}" width="100" height="100" /></a></dt>
           <dd>
            <h3><a href="/index/goodspart/{{$v->goods_id}}">{{$v->goods_name}}</a></h3>
            <div class="prolist-price"><strong>¥{{$v->goods_price}}</strong> <span>¥{{$v->market_price}}</span></div>
            <div class="prolist-yishou"><span>5.0折</span> <em>库存：{{$v->goods_num}}</em></div>
           </dd>
           <div class="clearfix"></div>
          </dl>
         </div>
     @endforeach
     <div class="joins"><a href="fenxiao.html"><img src="/jewell/images/jrwm.jpg" /></a></div>
     <div class="copyright">Copyright &copy; <span class="blue">这是就是三级分销底部信息</span></div>

     @include('public.footer')
     {{--<script>--}}
         {{--$(function(){--}}
             {{--$('.fr').click(function(){--}}
                 {{--var username=$(this).prev('input').val();--}}
                 {{--$.post(--}}
                         {{--"/index/search",--}}
                         {{--{username:username},--}}
                         {{--function(res){--}}
                             {{--$('#div').html(res);--}}
                         {{--}--}}
                 {{--)--}}
             {{--})--}}
         {{--})--}}
     {{--</script>--}}
     @endsection
     