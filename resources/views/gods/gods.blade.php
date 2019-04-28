<link type="text/css" rel="stylesheet" href="{{asset('css/page.css')}}">
<form action="" method="post">
请输入关键字：
              <input type="text" name="username" value="{{$username}}">
              <input type="submit" value="搜索">
</form>

<table border="1">
    <tr>
        <td>id</td>
        <td>商品名称</td>
        <td>商品图片</td>
        <td>商品数量</td>
        <td>商品描述</td>
        <td>操作</td>
    </tr>
    @foreach($res as $k=>$v)
    <tr>
        <td>{{$v->goods_id}}</td>
        <td><a href="/index/godsPart/{{$v->goods_id}}">{{$v->goods_name}}</a></td>
        <td>
            <img src="http://juploads.com/imgUpload/{{$v->goods_img}}" style="width:50px;height:50px;">
        </td>
        <td>{{$v->goods_num}}</td>
        <td>{{$v->goods_desc}}</td>
        <td>
            <a href="/index/goodsDel/{{$v->goods_id}}">删除</a>
            <a href="/index/goodsEdit/{{$v->goods_id}}">修改</a>
        </td>
    </tr>
    @endforeach
</table>
{{ $res->appends($data)->links() }}