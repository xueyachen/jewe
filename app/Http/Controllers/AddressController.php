<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class AddressController extends Controller
{
    //收货地址列表
    public function index(){
        //查询收货地址的数据
        $user_id=Request()->session()->get('user.user_id');
        $res=$this->addressInfo($user_id);
        return view('address.list',compact('res'));
    }
    //收货地址添加 显示视图
    public function add(){
        //查询省份信息
        $provinceInfo=$this->provinceInfo(0);
        return view('address.add',compact('provinceInfo'));
    }
    //查询市区信息
    public function change(){
        $id=Request()->id;
        $where=[
            'pid'=>$id
        ];
        $cityInfo=DB::table('area')->where($where)->get();
        return $cityInfo;
    }
    //收货地址添加
    public function addressAdd(){
        $data=Request()->all();
        $user_id=Request()->session()->get('user.user_id');
        $data['user_id']=$user_id;
        $data['create_time']=time();
        $where=[
            'user_id'=>$user_id
        ];
        $updatewhere=[
            'is_default'=>2,
            'create_time'=>time()
        ];
        if($data['is_default']==1){
            $res=DB::table('address')->where($where)->update($updatewhere);
        }
        $res=DB::table('address')->insert($data);
        if($res){
            echo 1;
        }else{
            echo 2;
        }
    }
    //收货地址删除
    public function del(){
       $id=Request()->address_id;
        $res=DB::table('address')->where('address_id',$id)->update(['address_status'=>2]);
        dd($res);
        if($res){
            echo 1;
        }else{
            echo 2;
        }
    }
    //收货地址修改
    public function edit($id){
        //根据id 查询当前的数据
        $result=DB::table('address')->where('address_id',$id)->first();
        //查询省份信息
        $provinceInfo=$this->provinceInfo(0);
        //查询直辖市
        $cityInfo=$this->provinceInfo($result->province);
        //查询区信息
        $areaInfo=$this->provinceInfo($result->city);

        return view('address.edit',compact('res','result','provinceInfo','cityInfo','areaInfo'));
    }
    //收货地址修改执行
    public function update(){
        $address_id=Request()->address_id;
        $data=Request()->all();
        $user_id=Request()->session()->get('user.user_id');
        if($data['is_default']==1){
            //将数据库出中的默认改为2
            //将数据添加到数据库
            $res1=DB::table('address')->where('user_id',$user_id)->update(['is_default'=>2]);
            $res2=DB::table('address')->where('address_id',$address_id)->update($data);
            if($res1!=0&&$res2!=0){
                echo 1;
            }else{
                echo 2;
            }
        }else{
            //直接修改
            $res=DB::table('address')->where('address_id',$address_id)->update($data);
            if($res){
                echo 1;
            }else{
                echo 2;
            }
        }
    }





    //省份数据
    public function provinceInfo($pid){
        //查询市区表 中的数据
        $areaInfo=DB::table('area')->where('pid',$pid)->get();
        return $areaInfo;
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
