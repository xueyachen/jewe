<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Cache;
class IndexController extends Controller
{
	//首页
    public function index(){
        $pagenum=config('app.pagenum',6);
        $new=cache('new');
        $best=cache('best');
        $cateInfo=cache('cateInfo');
        $topCateInfo=cache('topCateInfo');
        if(!$new||!$best||!$cateInfo){
            //查询新品
            $new=DB::table('goods')->where('goods_new',1)->paginate($pagenum);
            cache(['new'=>$new],60*24);
            //查询精品
            $best=DB::table('goods')->where('goods_best',1)->orderBy('goods_id','desc')->paginate($pagenum);
            cache(['best'=>$best],60*24);
            //查询分类
            $cateInfo=DB::table('cate')->get();
            cache(['cateInfo'=>$cateInfo],60*24);
            //查询顶级分类
            $topCateInfo=DB::table('cate')->where('pid',0)->get();
            cache(['topCateInfo'=>$topCateInfo],60*24);
        }
        //将用户ID 传给视图
        $user=Request()->session()->get('user');

        return view('index.index',compact('new','best','topCateInfo','user'));

    }





    //获取分类下的信息
    public function getCateInfo($cateInfo,$pid){
        Static $info=[];
        foreach($cateInfo as $k=>$v){
            if(($v->pid)==$pid){
                $info[]=$v;
                $this->getCateInfo($cateInfo,($v->cate_id));
            }
        }
        return $info;
    }

    //memcache
    public function test($id){
        $res=cache('res'.$id);
        if(!$res){
            $res=DB::table('goods')->where('goods_id',$id)->first();
            cache(['res'.$id=>$res],60*24);
        }
        return view('index.test',['res'=>$res]);
    }

}
