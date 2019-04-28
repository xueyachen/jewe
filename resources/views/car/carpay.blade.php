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
     <div class="dingdanlist">
      <table>

       <tr>
        <td class="dingimg" width="75%" colspan="2">
            @foreach($res as $k=>$v)
                @if($v->is_default==1)
                    <input type="radio" name="a" checked class="def" value="{{$v->address_id}}">姓名：{{$v->address_name}}地址{{$v->address_detail}}
                @else
                    <input type="radio" name="a" class="def" value="{{$v->address_id}}">姓名：{{$v->address_name}}地址{{$v->address_detail}}
                @endif
            @endforeach
        </td>
        <td align="right"><img src="/jewell/images/jian-new.png" /></td>
       </tr>

       <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">选择收货时间</td>
        <td align="right"><img src="/jewell/images/jian-new.png" /></td>
       </tr>
       <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">支付方式</td>
        <td align="right">

            <select name="pay_type" id="pay_status" class="hui">
                <option value="1">支付宝支付</option>
                <option value="2">银行卡支付</option>
                <option value="3">余额支付</option>
            </select>

        </td>
       </tr>
       <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">优惠券</td>
        <td align="right"><span class="hui">无</span></td>
       </tr>
       <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">是否需要开发票</td>
        <td align="right"><a href="javascript:;" class="orange">是</a> &nbsp; <a href="javascript:;">否</a></td>
       </tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">发票抬头</td>
        <td align="right"><span class="hui">个人</span></td>
       </tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">发票内容</td>
        <td align="right">
            <a href="javascript:;" class="hui">请选择发票内容</a></td>
       </tr>
       <tr><td colspan="3" style="height:10px; background:#fff;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="3">商品清单</td>
       </tr>
       
       @foreach($carGoodsInfo as $k=>$v)
           <tr goods_id="{{$v->goods_id}}" class="id">
            <td class="dingimg" width="15%"><img src="http://juploads.com/imgUpload/{{$v->goods_img}}" /></td>
            <td width="50%">
             <h3>{{$v->goods_name}}</h3>
             <time>下单时间：{{date('Y-m-d h:i:s',$v->create_time)}}</time>
            </td>
            <td align="right"><span class="qingdan">X{{$v->buy_number}}</span></td>
           </tr>
           <tr>
            <th colspan="3"><strong class="orange">¥{{$v->price}}</strong></th>
           </tr>
       @endforeach
      </table>
     </div><!--dingdanlist/-->
    </div><!--content/-->
    
    <div class="height1"></div>
    <div class="gwcpiao">
     <table>
      <tr>
       <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
       <td width="50%">总计：<strong class="orange">¥{{$allprice}}</strong></td>
       <td width="40%"><a href="javascript:;" class="jiesuan">提交订单</a></td>
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
        //提交订单
        $('.jiesuan').click(function(){
            //获取商品ID
            var id=$('.id');
            var goods_id='';
            id.each(function(index){
                goods_id+=$(this).attr('goods_id')+',';
            })
            goods_id=goods_id.substr(0,goods_id.length-1);
            //获取收货地址id

            var address_id=''
            $('.def:checked').each(function(){
                address_id+=$(this).val();
            })
            //获取支付方式
            var pay_type=$('#pay_status').val();
            $.post(
                    "/index/order",
                    {goods_id:goods_id,pay_type:pay_type,address_id:address_id},
                    function(res){
                        if(res==3){
                            alert('下单成功');
                        }
                        location.href='/index/success/'+res;
                    }
            )
        })
    })
</script>