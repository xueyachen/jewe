<table border="1">
    <tr>
        <td>商品:</td>
        <td>数量:</td>
        <td>详细信息:</td>
        <td>商品图片:</td>
    </tr>

    <tr>
        <td>{{$res['goods_name']}}</td>
        <td>{{$res['goods_num']}}</td>
        <td>{{$res['goods_desc']}}</td>
        <td><img src="http://juploads.com/imgUpload/{{$res['goods_img']}}" style="width:50px;height:50px;"></td>
    </tr>
</table>

