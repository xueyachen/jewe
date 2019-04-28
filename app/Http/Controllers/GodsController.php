<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Redis;
class GodsController extends Controller
{
    //列表展示
    public function index()
    {
        $username = Request()->username;
        $data = Request()->all();
        $username = $username??'';
        $where = [];
        if ($username) {
            $where[] = ['goods_name', 'like', "%$username%"];
        }

        $size = config('app.pagenum', 4);
        $res = DB::table('goods')->where($where)->where(['goods_new' => 1, 'status' => 1])->paginate($size);
        return view('gods.gods', compact('res', 'username', 'data'));
    }

    //删除
    public function goodsDel($id)
    {
        $res = DB::table('goods')->where('goods_id', $id)->update(['status' => 2]);
        if ($res) {
            cache(['res'.$id=>null],0);
            echo "<script>alert('删除成功');location.href='/index/gods'</script>";
        }

    }

    //修改
    public function goodsEdit($id)
    {
        $res=cache('res'.$id);
        if(!$res){
            echo 'db';
            $res = DB::table('goods')->where('goods_id', $id)->first();
            cache(['res'.$id=>$res],60*24);
        }
        $res=json_decode(json_encode($res),true);
        return view('gods.edit', compact('res'));
    }

    //修改执行
    public function update(Request $request,$id)
    {
        $res=cache('res'.$id);
        if(!$res){
            echo 'db';
            $data = Request()->all();
            //修改图片
            if (Request()->hasFile('img')) {
                $data['goods_img']=$this->uploads($request,'img');
                unset($data['img']);
            }
            $res = DB::table('goods')->where('goods_id', $id)->update($data);
            $res = DB::table('goods')->where('goods_id', $id)->first();
        }
        if($res){
            cache(['res'.$id=>$res],60*24);
            echo "<script>alert('修改成功');location.href='/index/gods';</script>";
        }
    }

    //文件上传
    public function uploads($request, $name)
    {
        if ($request->file($name)->isValid()) {
            $photo = $request->file($name);
            $extension = $photo->extension();
//            $store_result = $photo->storeAs(date('Ymd'), date('YmdHis') . rand(100, 999) . '.' . $extension);
            $store_result =$photo->store('images','s4');
            return $store_result;
        }
    }
    //详情
    public function godsPart($id){
        $res=cache('res'.$id);
        if(!$res){
            $res=DB::table('goods')->where('goods_id',$id)->first();
            cache(['res'.$id=>$res],60*24);
        }
        $res=json_decode(json_encode($res),true);
        return view('gods.godsPart',compact('res'));
    }
    //redis测试
    public function test($id){
        Redis::set('name','lisi');
        dump(Redis::get('name'));die;
        $arr=[
            'id'=>$id
        ];

        $res= cache(['arr'=>$arr],60*24);
        dump(cache()->get('arr'));

    }
}
