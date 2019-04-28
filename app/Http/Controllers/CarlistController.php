<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class CarlistController extends Controller
{
    //购物车列表
    public function index(){
        //取出session中的用户ID  根据用户id 查询购物车表中的商品id
        $user_id = Request()->session()->get('user.user_id');
        //俩表联查取出 car 和 goods 中的数据
        $carGoodsInfo=DB::table('car')
                ->join('goods','car.goods_id','=','goods.goods_id')
                ->where(['user_id'=>$user_id,'car_status'=>1])
                ->get();
//        dd($carGoodsInfo);
        //小计
        foreach($carGoodsInfo as $k=>$v){
            $carGoodsInfo[$k]->price=$v->goods_price*$v->buy_number;
        }
        //查询购物车中 商品的条数
        $count=DB::table('car')->where(['car_status'=>1,'user_id'=>$user_id])->count();

    	return view('car.carlist',compact('carGoodsInfo','count'));
    }
    //总价
    public function allcount(){
        $goods_id=Request()->goods_id;
        $goods_id=explode(',',$goods_id);
        //取出session中的用户ID  根据用户id 查询购物车表中的商品id
        $user_id = Request()->session()->get('user.user_id');
        $goodsInfo=DB::table('goods')->whereIn('goods_id',$goods_id)->get();
        $carInfo=DB::table('car')->where(['car_status'=>1,'user_id'=>$user_id])->whereIn('goods_id',$goods_id)->get();
        $allcount=0;
        foreach($goodsInfo as $k=>$v){
            foreach($carInfo as $key=>$val){
                if($v->goods_id==$val->goods_id){
                    $allcount+=$val->buy_number*$v->goods_price;
                }
            }
        }
        return $allcount;
    }
    //更改购买数量
    public function goodsInfoNum(){
        $goods_id=Request()->goods_id;
        $buy_number=Request()->buy_number;
        $user_id=Request()->session()->get('user.user_id');
        $where=[
            'user_id'=>$user_id,
            'goods_id'=>$goods_id
        ];

        $wherenum=[
            'user_id'=>$user_id,
            'goods_id'=>$goods_id
        ];
        $num=DB::table('car')->where($wherenum)->value('buy_number');
        $where1=[
            'buy_number'=>$buy_number
        ];
        //判断库存
        $goods_number=$this->carGoodsNum($goods_id,$buy_number);

        if($goods_number == true){
            $res=DB::table('car')->where($where)->update($where1);
        }else{
            echo 1;
        }


    }
    //购物车数据删除
    public function goodsdel(){
        $id=Request()->goods_id;
        $id=explode(',',$id);

        $res= DB::table('car')->whereIn('goods_id',$id)->update(['car_status'=>2]);
        if($res){
            echo 1;
        }else{
            echo 2;
        }
    }
    //去结算 判断是否登录
    public function goodspay(){
       $user= Request()->session()->get('user');
        if($user==''){
            echo 1;
        }else{
            echo 2;
        }
    }





    //判断库存
    public function carGoodsNum($goods_id,$buy_number,$num=0){
        $where=[
            'goods_id'=>$goods_id
        ];
        //总库存
        $goods_num=DB::table('goods')->where($where)->value('goods_num');
        if($buy_number+$num > $goods_num){
            return false;
        }else{
            return true;
        }
    }



}
