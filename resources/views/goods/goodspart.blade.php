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
       <h1>产品详情</h1>
      </div>
     </header>
     <div id="sliderA" class="slider">
         @foreach($imgs as $k=>$v)
         <img src="http://juploads.com/goodsImgUpload/{{$v}}"/>
         @endforeach
     </div><!--sliderA/-->
     <table class="jia-len">
      <tr>
       <th><strong class="orange">{{$goodsPartInfo->goods_price}}</strong></th>
       <td align="right">
           <button class="less">-</button>
           <input type="text" goods_num="{{$goodsPartInfo->goods_num}}" value="1" class="ipt" goods_id="{{$goodsPartInfo->goods_id}}"/>
           <button class="more">+</button>
       </td>
      </tr>
      <tr>
       <td>
        <strong>{{$goodsPartInfo->goods_name}}</strong>

       </td>
       <td align="right">
          @if($collect != '')
                <a href="javascript:;" class="shoucang" goods_id="{{$goodsPartInfo->goods_id}}" style="color:red"><span class="glyphicon glyphicon-star-empty"></span></a>
          @else
                <a href="javascript:;" class="shoucang" goods_id="{{$goodsPartInfo->goods_id}}"><span class="glyphicon glyphicon-star-empty"></span></a>
          @endif()
       </td>
      </tr>
     </table>
     <div class="height2"></div>
     <h3 class="proTitle">商品规格</h3>
     <ul class="guige">
      <li class="guigeCur"><a href="javascript:;">50ML</a></li>
      <li><a href="javascript:;">100ML</a></li>
      <li><a href="javascript:;">150ML</a></li>
      <li><a href="javascript:;">200ML</a></li>
      <li><a href="javascript:;">300ML</a></li>
      <div class="clearfix"></div>
     </ul><!--guige/-->
     <div class="height2"></div>
     <div class="zhaieq">
      <a href="javascript:;" class="zhaiCur">商品简介</a>
      <a href="javascript:;">商品参数</a>
      <a href="javascript:;" style="background:none;">订购列表</a>
      <div class="clearfix"></div>
     </div><!--zhaieq/-->
     <div class="proinfoList">
      <img src="http://juploads.com/imgUpload/{{$goodsPartInfo->goods_img}}" width="636" height="822" />
     </div><!--proinfoList/-->
     <div class="proinfoList">
      暂无信息....
     </div><!--proinfoList/-->
     <div class="proinfoList">
      暂无信息......
     </div><!--proinfoList/-->
     <table class="jrgwc">
      <tr>
       <th>
        <a href="index.html"><span class="glyphicon glyphicon-home"></span></a>
       </th>
       <td>
           <button id="car">加入购物车</button>
       </td>
      </tr>
     </table>
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
     <!--jq加减-->
    <script src="/jewell/js/jquery.spinner.js"></script>
   <script>
	$('.spinnerExample').spinner({});
	</script>
  </body>
</html>

<script>
    $(function(){
        //点击加号
        $(document).on('click','.more',function(){
            var _this=$(this);
            //获取当前的购买数量
            var buy_number=parseInt(_this.prev('input').val());
            //获取该件商品的总库存
            var goods_num=_this.prev('input').attr('goods_num');
            if(buy_number>=goods_num){
                _this.prev('input').prop('disabled',true);
                _this.prev('input').prop('disabled',false);
            }else{
                buy_number=buy_number+1;
                _this.prev('input').val(buy_number);
                _this.prev('input').prop('disabled',false);
            }
        })
        //点击减号
        $(document).on('click','.less',function(){
            var _this=$(this);
            //获取当前的购买数量
            var buy_number=parseInt(_this.next('input').val());
            //获取该件商品的总库存
            var goods_num=_this.next('input').attr('goods_num');
            if(buy_number<=1){
                _this.next('input').prop('disabled',true);
                _this.next('input').prop('disabled',false);
            }else{
                buy_number=buy_number-1;
                _this.next('input').val(buy_number);
                _this.next('input').prop('disabled',false);
            }
        })
        //失去焦点
        $(document).on('blur','.ipt',function(){
            var _this=$(this);
            //获取当前的购买数量
            var buy_number=parseInt(_this.val());
            //获取该件商品的总库存
            var goods_num=_this.attr('goods_num');
            var reg=/^\d{1,}$/;
            if(buy_number>=goods_num){
                _this.val(goods_num);
            }else if(!reg.test(buy_number)){
                _this.val(1);
            }else if(buy_number<=1){
                _this.val(1);
            }else{
                _this.val(buy_number);
            }
        })
        //收藏
        $(document).on('click','.shoucang',function(){
            var _this=$(this);
            //获取当前商品的id
            var goods_id=$(this).attr('goods_id');
            $.post(
                    "/index/collect",
                    {goods_id:goods_id},
                    function(res){
                        if(res==1){
                            alert('收藏成功');
                            _this.prop('style','color:red');
                        }else{
                            alert('已收藏');
                        }
                    }
            )



        })
        //点击加入购物车
        $('#car').click(function(){
            //获取购买数量
            var buy_number=parseInt($('.ipt').val());
            //获取商品id
            var goods_id=$('.ipt').attr('goods_id');
            $.post(
                    "/index/caradd",
                    {buy_number:buy_number,goods_id:goods_id},
                    function(res){
//                        console.log(res);
                        if(res==1){
                            alert('库存不足');
                        }else if(res=='ok'){
                            alert('加入购物车成功');
                            location.href='/index/carlist';
                        }else if(res=='no'){
                            alert('加入购物车失败');
                        }
                    }
            )
        })
    })
</script>