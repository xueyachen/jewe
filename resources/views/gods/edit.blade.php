<form action="/index/update/{{$res['goods_id']}}" method="post" enctype='multipart/form-data'>
    <p>商品名称：<input type="text" name="goods_name" value="{{$res['goods_name']}}"></p>
    <p>
        商品图片：
        <img src="http://juploads.com/imgUpload/{{$res['goods_img']}}" style="width:50px;height:50px;">
        <input type="hidden" name="goods_img" value="{{$res['goods_img']}}">
        <input type="file" name="img">
    </p>
    <p>商品数量：<input type="text" name="goods_num" value="{{$res['goods_num']}}"></p>
    <p>商品描述：<input type="text" name="goods_desc" value="{{$res['goods_desc']}}"></p>
    <p><input type="submit" value="修改"></p>
</form>