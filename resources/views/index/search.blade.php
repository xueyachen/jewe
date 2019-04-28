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