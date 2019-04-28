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
     <table class="shoucangtab">
      <tr>
       <td width="75%"><a href="/index/addressadd" class="hui"><strong class="">+</strong> 新增收货地址</a></td>
       <td width="25%" align="center" style="background:#fff url(/jewell/images/xian.jpg) left center no-repeat;"><a href="javascript:;" class="orange">删除信息</a></td>
      </tr>
     </table>
     
     <div class="dingdanlist" >
              <table>
          @foreach($res as $k=>$v)
              @if($v->is_default==1)
                   <tr style="color:red">
                    <td width="50%">
                     <h3>{{$v->address_name}}{{$v->address_tel}}</h3>
                     <time style="color:red">{{$v->province}}{{$v->city}}{{$v->area}}{{$v->address_detail}}</time>
                    </td>
                    <td align="right">
                        <a href="javascript:;" class="del" address_id="{{$v->address_id}}"><span class="glyphicon glyphicon-check"></span> 删除信息</a>
                        <a href="/index/edit/{{$v->address_id}}" class="edit" address_id="{{$v->address_id}}"><span class="glyphicon glyphicon-check"></span> 修改信息</a>
                    </td>
                   </tr>
              @else
                   <tr>
                     <td width="50%">
                       <h3>{{$v->address_name}}{{$v->address_tel}}</h3>
                       <time>{{$v->province}}{{$v->city}}{{$v->area}}{{$v->address_detail}}</time>
                     </td>
                     <td align="right">
                         <a href="javascript:;" class="del" address_id="{{$v->address_id}}"><span class="glyphicon glyphicon-check"></span> 删除信息</a>
                         <a href="/index/edit/{{$v->address_id}}" class="edit" address_id="{{$v->address_id}}"><span class="glyphicon glyphicon-check"></span> 修改信息</a></td>
                   </tr>
              @endif
         @endforeach
              </table>

     </div><!--dingdanlist/-->
     
     <div class="height1"></div>
     <div class="footNav">
      <dl>
       <a href="index.html">
        <dt><span class="glyphicon glyphicon-home"></span></dt>
        <dd>微店</dd>
       </a>
      </dl>
      <dl>
       <a href="prolist.html">
        <dt><span class="glyphicon glyphicon-th"></span></dt>
        <dd>所有商品</dd>
       </a>
      </dl>
      <dl>
       <a href="car.html">
        <dt><span class="glyphicon glyphicon-shopping-cart"></span></dt>
        <dd>购物车 </dd>
       </a>
      </dl>
      <dl class="ftnavCur">
       <a href="user.html">
        <dt><span class="glyphicon glyphicon-user"></span></dt>
        <dd>我的</dd>
       </a>
      </dl>
      <div class="clearfix"></div>
     </div><!--footNav/-->
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
        //删除
        $(document).on('click','.del',function(){
            var address_id=$(this).attr('address_id');
            $.post(
                    "/index/del",
                    {address_id:address_id},
                    function(res){
                        alert('删除成功');
                        if(res==1){
                            alert('删除成功');
                            history.go(0);
                        }
                    }
            )
        })
    })
</script>