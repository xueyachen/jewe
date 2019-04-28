<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class CarpayController extends Controller
{
    //结算商品页面
    public function index($id){
        if($id==''){
            echo 1;
        }else{
            //购物车传过来的id 查询商品的数据
            $carGoodsInfo=$this->carGoodsInfo($id);
            //总价
            $allprice=0;
            foreach($carGoodsInfo as $k=>$v){
                $carGoodsInfo[$k]->price=$v->goods_price*$v->buy_number;//小计
                $allprice+=$v->goods_price*$v->buy_number;//总价
            }
            //查询收货地址的信息
            $user_id=Request()->session()->get('user.user_id');
            $res=$this->addressInfo($user_id);
        }
    	return view('car.carpay',compact('carGoodsInfo','allprice','res'));
    }




    //购物车商品结算数据
    public function carGoodsInfo($id){
        $id=explode(',',$id);
        $user_id= Request()->session()->get('user.user_id');
        $where=[
            'user_id'=>$user_id,
            'car_status'=>1
        ];
        //拿到结算的商品的信息
        $res=DB::table('car')
            ->join('goods','car.goods_id','=','goods.goods_id')
            ->where($where)
            ->whereIn('car.goods_id',$id)
            ->get();
        return $res;
    }
    //收货地址列表
    public function addressInfo($user_id){
        $where=[
            'user_id'=>$user_id,
            'address_status'=>1
        ];
        $res=DB::table('address')->where($where)->get();
        foreach($res as $k=>$v){
            $res[$k]->province=DB::table('area')->where('id',$v->province)->value('name');
            $res[$k]->city=DB::table('area')->where('id',$v->city)->value('name');
            $res[$k]->area=DB::table('area')->where('id',$v->area)->value('name');
        }
        return $res;
    }
}
