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
       <h1>购物车</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="/jewell/images/head.jpg" />
     </div><!--head-top/-->
     <table class="shoucangtab">
      <tr>
       <td><input type="checkbox" class="allbox"></td>
       <td width="75%"><span class="hui">购物车共有：<strong class="orange">{{$count}}</strong>件商品</span></td>
       <td width="25%" align="center" style="background:#fff url(/jewell/images/xian.jpg) left center no-repeat;">
        <span class="glyphicon glyphicon-shopping-cart" style="font-size:2rem;color:#666;"></span>
       </td>
      </tr>
     </table>
     <div class="dingdanlist">
      <table>
        @foreach($carGoodsInfo as $k=>$v)
           <tr goods_id="{{$v->goods_id}}" price="{{$v->goods_price}}">
            <td width="4%"><input type="checkbox" value="{{$v->goods_id}}" class="box"/></td>
            <td class="dingimg" width="15%"><img src="http://juploads.com/imgUpload/{{$v->goods_img}}" /></td>
            <td width="50%">
             <h3>{{$v->goods_name}}</h3>
             <time>下单时间：{{date('Y-m-d h:i:s',$v->create_time)}}</time>
            </td>
            <td align="right">
                <div>
                    <button class="less" goods_id="{{$v->goods_id}}">-</button>
                    <input type="text" class="ipt" value="{{$v->buy_number}}" goods_num="{{$v->goods_num}}"/>
                    <button class="more" goods_id="{{$v->goods_id}}">+</button>
                </div>
            </td>
           </tr>
           <tr goods_id="{{$v->goods_id}}">
            <th colspan="4" class="getcore">
                小计：<strong class="orange">¥{{$v->price}}</strong>
                <a href="javascript:;" class="del">删除</a>
            </th>
           </tr>
        @endforeach
       <tr>
        <td width="100%" colspan="4"><a href="javascript:;" class="alldel"><input type="checkbox" name="1" /> 删除</a></td>
       </tr>
      </table>
     </div><!--dingdanlist/-->
     <div class="height1"></div>
     <div class="gwcpiao">
     <table>
      <tr>
       <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
       <td width="50%">总计：<strong class="orange" id="allcount">¥0</strong></td>
       <td width="40%"><a href="javascript:;" class="jiesuan">去结算</a></td>
      </tr>
     </table>
    </div><!--gwcpiao/-->
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
        //全选 反选
        $(document).on('click','.allbox',function(){
            var box=$(this).prop('checked');
            var allbox=$('.box').prop('checked',box);
            //总价
            allCount()

        })
        //点击加号
        $(document).on('click','.more',function(){
            var _this=$(this);
            //获取当前购买的数量
            var buy_number=parseInt($(this).prev('input').val());
            //获取该商品的总库存
            var goods_num=$(this).prev('input').attr('goods_num');
//            alert(goods_num);
            if(buy_number>=goods_num){
                $(this).prop('disabled',true);
                $(this).prev().prev().prop('disabled',false);
            }else{
                buy_number=buy_number+1;
                $(this).prev('input').val(buy_number);
                $(this).prev().prev().prop('disabled',false);
            }
            //获取商品id
            var goods_id=$(this).attr('goods_id');
            //购买数量
            goodsInfoNum(buy_number,goods_id);
            //更改小计
            getCore(_this,buy_number);
            //总价
            allCount();
        })
        //点击减号
        $(document).on('click','.less',function(){
            var _this=$(this);
            //获取当前购买的数量
            var buy_number=parseInt($(this).next('input').val());
            //获取该商品的总库存
            var goods_num=$(this).next('input').attr('goods_num');
            if(buy_number<=1){
                _this.prop('disabled',true);
                _this.next().next().prop('disabled',false);
            }else{
                buy_number=buy_number-1;
                _this.next('input').val(buy_number);
                _this.next().next().prop('disabled',false);
            }
            //获取商品id
            var goods_id=$(this).attr('goods_id');
            //购买数量
            goodsInfoNum(buy_number,goods_id);
            //更改小计
            getCore(_this,buy_number);
            //总价
            allCount();
        })
        //失去焦点
        $(document).on('blur','.ipt',function(){
            var _this=$(this);
            //获取当前购买的数量
            var buy_number=parseInt(_this.val());

            //获取该商品的总库存
            var goods_num=_this.attr('goods_num');
            var reg=/^\d{1,}$/;
            if(buy_number==''||buy_number<=1||!reg.test(buy_number)){
                _this.val(1);
            }else if(buy_number>=goods_num){
                _this.val(goods_num);
            }else{
                _this.val(buy_number);
            }
                //获取当前购买的数量
                var buy_number=parseInt(_this.val());
                //获取商品id
                var goods_id=$(this).next('button').attr('goods_id');
                //购买数量
                goodsInfoNum(buy_number,goods_id);
                //更改小计
                getCore(_this,buy_number);
                //总价
                allCount();

        })
        //去结算
        $(document).on('click','.jiesuan',function(){
            var _this=$(this);
            //拿到选中的商品的id
            var _box=$('.box');
            var goods_id='';
            $('.box:checked').each(function(i,k){
                goods_id+=$(this).val()+',';
            })
            goods_id=goods_id.substr(0,goods_id.length-1);
            if(goods_id==''){
                alert('请至少选择一件商品');
                return false;
            }
            $.post(
                    "/index/goodspay",
                    'post',
                    function(res){
                        if(res==1){
                            alert('您还未登录，请先登录');
                            location.href='/login';
                        }else{
                            location.href='/index/carpay/'+goods_id;
                        }
                    }
            )
        })
        //删除
        $(document).on('click','.del',function(){
            var goods_id=$(this).parents('tr').attr('goods_id');
            $.post(
                    "/index/goodsdel",
                    {goods_id:goods_id},
                    function(res){
                        if(res==1){
                            alert('删除成功');
                            history.go(0);
                        }
                    }
            )
        })
        //批删
        $(document).on('click','.alldel',function(){
            var goods_id='';
           _box=$('.box:checked').each(function(index){
               goods_id+=$(this).val()+',';
           })
           goods_id=goods_id.substr(0,goods_id.length-1);
            $.post(
                    "/index/goodsdel",
                    {goods_id:goods_id},
                    function(res){
                        if(res==1){
                            alert('删除成功');
                            history.go(0);
                        }
                    }
            )
        })


        //总价
        function allCount(){
            var _box=$('.box');
            var goods_id='';
            _box.each(function(index){
                if($(this).prop('checked')==true){
                    goods_id+=$(this).parents('tr').attr('goods_id')+',';
                }
            })
            goods_id=goods_id.substr(0,goods_id.length-1);
            $.post(
                    "/index/allcount",
                    {goods_id:goods_id},
                    function(res){
                        $('#allcount').text('￥'+res);
                    }
            )
        }
        //点击复选框
        $('.box').click(function(){
            $(this).prop('checked');
            //总价
            allCount()
        })
        //购买数量
        function goodsInfoNum(buy_number,goods_id){
            $.ajax({
                    method: "post",
                    url: "/index/goodsInfoNum",
                    data: {goods_id:goods_id,buy_number:buy_number},
                    async:false
            }).done(function(res){
                if(res==1){
                    alert('库存不足');
                }

            });

        }
        //更改小计
        function getCore(_this,buy_number){
            var price=_this.parents('tr').attr('price');
            var getcore=price*buy_number;
            _this.parents('tr').next('tr').find('strong').text('￥'+getcore);
        }
    })
</script>