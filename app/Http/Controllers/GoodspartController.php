<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Cookie;
class GoodspartController extends Controller
{
    //详情页面
    public function index(){
        //接受id
        $id=Request()->id;
        //根据id 查询这件商品的信息
        $goodsPartInfo=DB::table('goods')->where('goods_id',$id)->first();
        //将轮播图拆分
        $goods_imgs=$goodsPartInfo->goods_imgs;
//        dd($goods_img);
        $imgs=explode('|',rtrim($goods_imgs,'|'));
//        dd($imgs);
        //收藏样式
        $user_id=Request()->session()->get('user.user_id');
        $where=[
            'user_id'=>$user_id,
            'goods_id'=>$id
        ];
        $collect=DB::table('part')->where($where)->first();
//        dd($collect);
    	return view('goods/goodspart',compact('goodsPartInfo','imgs','collect'));
    }
    //加入购物车
    public function caradd(){
        $buy_number=Request()->buy_number;
        $goods_id=Request()->goods_id;
        //判断用户是否登录
        $user_id = Request()->session()->get('user.user_id');
        if($user_id==''){
            //将加入购物车数据存入cookie中
            $this->carGoodsCookie($goods_id,$buy_number,$user_id);
        }else{
            //将加入购物车数据存入数据库中
            $this->carGoodsDb($goods_id,$buy_number,$user_id);

        }
    }
    //将加入购物车数据存入数据库中
    public function carGoodsDb($goods_id,$buy_number,$user_id){
        $where=[
            'goods_id'=>$goods_id,
            'user_id'=>$user_id,
            'car_status'=>1
        ];
        //先判断数据库中是否有这件商品
        $count=DB::table('car')->where($where)->count();
        if($count==''){
            //没有的话做添加
            //先判断库存
            $num=$this->carGoodsNum($goods_id,$buy_number,0);
            if($num==false){
                echo 1;
                return false;
            }
            $arr=[
                'goods_id'=>$goods_id,
                'user_id'=>$user_id,
                'buy_number'=>$buy_number,
                'create_time'=>time()
            ];
            $res=DB::table('car')->insert($arr);
            if($res){
                echo 'ok';
            }else{
                echo 'no';
            }
        }else{
            //有的话做累加
            //求出库里的已经购买的数量
            $carwhere=[
                'user_id'=>$user_id,
                'goods_id'=>$goods_id
            ];
            $num=Db::table('car')->where($carwhere)->value('buy_number');
            //先判断库存
            $num=$this->carGoodsNum($goods_id,$buy_number,$num);
            if($num==false){
                echo 1;
            }else{
                $buy_num=DB::table('car')->where($where)->value('buy_number');
                $arr=[
                    'buy_number'=>$buy_num+$buy_number,
                    'update_time'=>time()
                ];
                $res=DB::table('car')->where($where)->update($arr);
                if($res){
                    echo 'ok';
                }else{
                    echo 'no';
                }
            }

        }
    }
    //将加入购物车数据存入cookie中
    public function carGoodsCookie($goods_id,$buy_number,$user_id){
        //先判断cookie中是否有数据
        $str=Request()->cookie('car');
        if($str==''){
            //判断库存
            $num=$this->carGoodsNum($goods_id,$buy_number,$num=0);
            if($num==false){
                echo 1;
            }else{
                //做第一次添加数据
                $arr['h_'.$goods_id]=[
                    'goods_id'=>$goods_id,
                    'create_time'=>time(),
                    'buy_number'=>$buy_number
                ];
                $str=base64_encode(serialize($arr));
                Cookie::queue('car',$str,60*60*24*1);
                echo 'ok';
            }
        }else{
            //判断cookie中是否有这件商品
            $str=Cookie::get('car');
            $arr=unserialize(base64_decode($str));
            //判断 键名是否在数组中
            if(array_key_exists('h_'.$goods_id,$arr)){
                //判断库存
                $num=$this->carGoodsNum($goods_id,$buy_number,$arr['h_'.$goods_id]['buy_number']);
                if($num==false){
                    echo 1;
                }else{
                    //在的话  做累加
                    $arr['h_'.$goods_id]['buy_number']=$arr['h_'.$goods_id]['buy_number']+$buy_number;
                    $str=base64_encode(serialize($arr));
                    Cookie::queue('car',$str,60*60*24*1);
                    echo 'ok';
                }
            }else{
                //判断库存
                $num=$this->carGoodsNum($goods_id,$buy_number,$num=0);
                if($num==false){
                    echo 1;
                }else{
                    //不在的话 做添加
                    $arr['h_'.$goods_id]=[
                        'goods_id'=>$goods_id,
                        'create_time'=>time(),
                        'buy_number'=>$buy_number
                    ];
                    $str=base64_encode(serialize($arr));
                    Cookie::queue('car',$str,60*60*24*1);
                    echo 'ok';
                }
            }

        }
    }
    //商品收藏
    public function collect(){
        $goods_id=Request()->goods_id;
        $user_id=Request()->session()->get('user.user_id');
        $where=[
            'user_id'=>$user_id,
            'goods_id'=>$goods_id
        ];
        $count=DB::table('part')->where($where)->count();
        if($count==0){
            $res=DB::table('part')->insert($where);
            if($res){
                echo 1;
            }
        }else{
            echo 2;
        }
    }




    //判断库存
    public function carGoodsNum($goods_id,$buy_number,$num){
        $where=[
            'goods_id'=>$goods_id
        ];
        //总库存
        $goods_num=DB::table('goods')->where($where)->value('goods_num');
        if($buy_number+$num<=$goods_num){
            return true;
        }else{
            return false;
        }
    }
    //测试cookie
    public function test(){
        $aa=Cookie::get('car');
        $bb=unserialize(base64_decode($aa));
        dd($bb);
    }
}
