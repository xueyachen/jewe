<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class GoodslistController extends Controller
{
    //商品列表
    public function index()
    {
        //点击全部商品  或者  点击分类  进入列表页面
        $cate_id = Request()->id;
        $username = request()->username;
        if (!empty($cate_id)) {
            //查询所有分类
            $cateInfo = DB::table('cate')->get();
            //查询分类下的分类id
            $c_id = $this->cateFloorInfo($cateInfo, $cate_id);
            $goodsInfo = DB::table('goods')->whereIn('cate_id', $c_id)->get();
        } else if (!empty($username)) {
            $where = [['goods_name', 'like', "%$username%"]];
            $goodsInfo = DB::table('goods')->where($where)->get();
        } else {
            $goodsInfo = DB::table('goods')->get();
        }

        return view('goods.goodslist', compact('goodsInfo', 'cate_id', 'username'));
    }

    //商品列表筛选
    public function screen()
    {
        $field = Request()->field;
        $cate_id = Request()->cate_id;
        $username = Request()->username;



        $c_id = [];
        $where = [];
        //点击分类
        if (!empty($cate_id)) {
            //查询所有分类
//            $cateInfo = DB::table('cate')->get();
//            //查询分类下的分类id
//            $c_id = $this->cateFloorInfo($cateInfo, $cate_id);

            //查询所有分类
            $cateInfo=cache('cateInfo');
            $c_id=cache('c_id'.$cate_id);


            //新品
            if($field=='goods_new'){
                $where=[
                    'goods_new'=>1
                ];
            }
            $order='asc';
            //价格
            if($field=='goods_price'){
                $order='asc';
            }
            //库存
            if($field=='goods_num'){
                $order='asc';
            }
            //点击分类 点击新品
            $res = DB::table('goods')->where($where)->whereIn('cate_id',$c_id)->orderBy($field,$order)->get();
            //在搜索的数据中 点击新品
        }else if(!empty($username)){
            //新品
            if($field=='goods_new'){
                $where=[
                    ['goods_name','like',"%$username%"],
                    ['goods_new','=',1]
                ];
            }
            //价格
            $order='asc';
            if($field=='goods_price'){
                $where=[
                    ['goods_name','like',"%$username%"]
                ];
                $order='asc';
            }
            //库存
            if($field=='goods_num'){
                $where=[
                    ['goods_name','like',"%$username%"]
                ];
                $order='asc';
            }
            $res = DB::table('goods')->where($where)->orderBy($field,$order)->get();
        //否则全部商品点击新品
        }else{
            //分类id 为空 走搜索 和 全部商品
            //新品
            if($field=='goods_new'){
                $where=[
                    'goods_new'=>1
                ];
            }
            $order='asc';
            //价格
            if($field=='goods_price'){
                $order='asc';
            }
            //库存
            if($field=='goods_num'){
                $order='asc';
            }
            //点击全部商品
            $res = DB::table('goods')->where($where)->orderBy($field,$order)->get();
        }

        return view('div.div', compact('res'));
    }




    //获取分类下的信息
    public function getCateInfo($cateInfo, $pid)
    {
        Static $info = [];
        foreach ($cateInfo as $k => $v) {
            if (($v->pid) == $pid) {
                $info[] = $v;
                $this->getCateInfo($cateInfo, ($v->cate_id));
            }
        }
        return $info;
    }

    //获取分类id
    public function cateFloorInfo($cateInfo, $pid)
    {
        static $arr = [];
        foreach ($cateInfo as $k => $v) {
            if (($v->pid) == $pid) {
                $arr[] = $v->cate_id;
                $this->cateFloorInfo($cateInfo, ($v->cate_id));
            }
        }
        return $arr;
    }
}
