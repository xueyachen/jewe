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
  <input type="hidden" value="{{$cate_id}}" id="cate_id">
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <form action="#" method="get" class="prosearch"><input type="text" value="{{$username}}" id="ipt"/></form>
      </div>
     </header>

     <ul class="pro-select">
          <li class="aa"><a href="javascript:;" field="goods_new" type="1">新品</a></li>
          <li class="aa"><a href="javascript:;" field="goods_num" type="2">库存</a></li>
          <li class="aa"><a href="javascript:;" field="goods_price" type="3">价格</a></li>
         <input type="hidden" value="{{$cate_id}}" class="hidden">
     </ul>

     <div class="prolist" id="div">
         @foreach($goodsInfo as $k=>$v)
          <dl>
           <dt><a href="/index/goodspart/{{$v->goods_id}}"><img src="http://juploads.com/imgUpload/{{$v->goods_img}}" width="100" height="100" /></a></dt>
           <dd>
            <h3><a href="/index/goodspart/{{$v->goods_id}}">{{$v->goods_name}}</a></h3>
            <div class="prolist-price"><strong>¥{{$v->goods_price}}</strong> <span>¥{{$v->market_price}}</span></div>
            <div class="prolist-yishou"><span>5.0折</span> <em>库存：{{$v->goods_num}}</em></div>
           </dd>
           <div class="clearfix"></div>
          </dl>
        @endforeach
     </div><!--prolist/-->
    @include('public.footer')
    
    </div><!--maincont-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="/jewell/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/jewell/js/bootstrap.min.js"></script>
    <script src="/jewell/js/style.js"></script>
    <!--焦点轮换-->
    <script src="/jewell/js/jquery.excoloSlider.js"></script>
    <script>
		$(function () {
		 $("#sliderA").excoloSlider();
		});
	</script>
  </body>
</html>

<script>
    $(function(){
        //更改 新品 销量 价格 的样式
        $('.aa').click(function(){
            $(this).addClass('pro-selCur');
            $(this).siblings('li').removeClass('pro-selCur');

            //获取分类id
            var cate_id=$('.hidden').val();
            var username=$('#ipt').val();

            var type=$(this).children('a').attr('type');
            if(type==1){
                field='goods_new';
            }else if(type==2){
                field='goods_num';
            }else{
                field='goods_price';
            }
            $.post(
                    "/index/screen",
                    {field:field,cate_id:cate_id,username:username},
                    function(res){
//                        console.log(res);
                        $('#div').html(res);
                    }
            )
        })




    })
</script>