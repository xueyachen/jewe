<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use \Log;
class SuccessController extends Controller
{
    //订单视图
    public function index($id){
        //根据订单id 查询订单表中的数据
        $res=DB::table('order')->where('order_id',$id)->first();

    	return view('success.success',compact('res'));
    }
    //提交订单
    public function order(){
        $data=Request()->all();
        $user_id=Request()->session()->get('user.user_id');
        $pay_type=Request()->pay_type;
        $address_id=Request()->address_id;
        //将商品的总价格  支付方式  订单号存入订单表中
            $orderInfo['order_no']=$this->orderNo();//订单号
            $orderInfo['order_amount']=$this->amount($data['goods_id']);//总价格
            $orderInfo['pay_type']=$pay_type;//支付方式
            $orderInfo['create_time']=time();
            $orderInfo['user_id']=$user_id;
            $orderInfo=DB::table('order')->insert($orderInfo);

        //将商品信息存入订单详情表
            //获取订单表最后一次添加的id
            $order_id=DB::getPdo()->lastInsertId();
            //查询订单结算的商品的信息
            $orderdetail=$this->payGoodsInfo($data['goods_id']);
            //将对象转换成数组
            $orderdetail=json_encode($orderdetail);
            $orderdetail=json_decode($orderdetail,true);
            foreach($orderdetail as $k=>$v){
                $orderdetail[$k]['user_id']=$user_id;
                $orderdetail[$k]['order_id']=$order_id;
            }
            $orderdetail=DB::table('detail')->insert($orderdetail);

        //将收货地址信息存入订单收货地址表中
            $addressInfo=DB::table('address')->where('address_id',$address_id)->select('address_name','province','city','area','address_tel','address_mail','user_id','create_time')->first();
            $addressInfo=json_encode($addressInfo);
            $addressInfo=json_decode($addressInfo,true);
            $addressInfo['order_id']=$order_id;
            $addressInfo=DB::table('adres')->insert($addressInfo);

        //删除购物车的数据
            $where1=[
                'user_id'=>$user_id,
                'car_status'=>1
            ];
            $updatewhere=[
                'car_status'=>2
            ];
            $goods_id=explode(',',$data['goods_id']);
            $res1=DB::table('car')->where($where1)->whereIn('goods_id',$goods_id)->update($updatewhere);

        //将少商品表中的库存
            $res2=DB::table('car')
                ->join('goods','car.goods_id','=','goods.goods_id')
                ->where('user_id',$user_id)
                ->whereIn('car.goods_id',$goods_id)
                ->get();
            $res2=json_encode($res2);
            $res2=json_decode($res2,true);

//            foreach($res2 as $k=>$v){
//                $updatewhere=[
//                    'goods_num'=>$v['goods_num']-$v['buy_number']
//                ];
//                $res2=DB::table('goods')->where('goods_id',$v['goods_id'])->update($updatewhere);
//            }

        if($orderInfo&&$orderdetail&&$addressInfo&&$res1){
            return $order_id;
        }
    }
    //立即支付
    public function aipay($order_no){
        if(!$order_no){
            return redirect('/index/carpay/{id}')->with('没有此订单信息');
        }
        //根据订单号 查询订单的商品总价
        $order_amount=DB::table('order')->where('order_no',$order_no)->value('order_amount');
        if($order_amount<=0){
            return redirect('/index/carpay/{id}')->with('此订单无效');
        }
        $config=config('alipaypage');

        require_once app_path('libs\alipay.page\pagepay\service\AlipayTradeService.php' );
        require_once app_path('libs\alipay.page\pagepay\buildermodel\AlipayTradePagePayContentBuilder.php');

        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = trim($order_no);

        //订单名称，必填
        $subject = '测试';

        //付款金额，必填
        $total_amount = $order_amount;

        //商品描述，可空
        $body = '测试';

        //构造参数
        $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);

        $aop = new \AlipayTradeService($config);

        /**
         * pagePay 电脑网站支付请求
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
         */
        $response = $aop->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);

        //输出表单
        var_dump($response);

    }
    //同步通知
    public function async(){
        $config=config('alipaypage');
        require_once app_path('libs/alipay.page/pagepay/service/AlipayTradeService.php');
        $arr=$_GET;
        $alipaySevice = new \AlipayTradeService($config);
        //验证便签  config和libs里的公钥得换成是支付宝公钥
        $result = $alipaySevice->check($arr);
        if($result){
            //商户订单号
            $where['order_no']= htmlspecialchars($_GET['out_trade_no']);

            //价格
            $where['order_amount'] = htmlspecialchars($_GET['total_amount']);
            //支付宝交易号
            $trade_no = htmlspecialchars($_GET['trade_no']);
            $count=DB::table('order')->where($where)->count();
            $result=json_encode($arr);
            //检测价格 单号是否在数据库中
            if(!$count){
                //写入log日志
                Log::channel('alipay')->info('订单单号和价格不符，没有当前记录'.$result."支付宝交易号：".$trade_no);
            }
            //检测商户id是否相等     应用appid
            if(htmlspecialchars($_GET['seller_id'])!=config('alipaypage.seller_id') || htmlspecialchars($_GET['app_id'])!=config('alipaypage.app_id')){
                Log::channel('alipay')->info('商品不符'.$result."支付宝交易号：".$trade_no);
            }
            return redirect('/index');
        }else{
            //验证失败
            echo '验证失败';
        }

    }
    //异步通知
    public function notify(){
//        Log::channel('alipay')->info('异步通知:test');die;
        $config=config('alipaypage');
        dd($config);
        require_once app_path('libs\alipay.page\pagepay\service\AlipayTradeService.php' );

        $arr=$_POST;
        $alipaySevice = new \AlipayTradeService($config);
        $alipaySevice->writeLog(var_export($_POST,true));
        $result = $alipaySevice->check($arr);
        Log::channel('alipay')->info('异步通知'.$result);
        die;
        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代


            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

            //商户订单号

            $out_trade_no = $_POST['out_trade_no'];

            //支付宝交易号

            $trade_no = $_POST['trade_no'];

            //交易状态
            $trade_status = $_POST['trade_status'];


            if($_POST['trade_status'] == 'TRADE_FINISHED') {

                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序

                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            }
            else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //付款完成后，支付宝系统发送该交易状态通知
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            echo "success";	//请不要修改或删除
        }else {
            //验证失败
            echo "fail";

        }
    }
    //订单号
    public function orderNo(){
        $user_id=Request()->session()->get('user.user_id');
        $orderno=rand(1000,9999).date('Ymd');
        return $orderno;
    }
    //总价格
    public function amount($goods_id){
        $goods_id=explode(',',$goods_id);
        $user_id=Request()->session()->get('user.user_id');
        $where=[
            'user_id'=>$user_id,
            'car_status'=>1
        ];
        $res=DB::table('car')
            ->join('goods','car.goods_id','=','goods.goods_id')
            ->where($where)
            ->whereIn('car.goods_id',$goods_id)
            ->get();
        $allprice=0;
        foreach($res as $k=>$v){
            $allprice+=$v->goods_price*$v->buy_number;
        }
        return $allprice;
    }
    //结算的商品信息
    public function payGoodsInfo($goods_id){
        $goods_id=explode(',',$goods_id);
        $user_id=Request()->session()->get('user.user_id');
        $where=[
            'user_id'=>$user_id,
            'car_status'=>1
        ];
        $res=DB::table('car')
            ->select('car.goods_id','buy_number','goods_price','goods_name','goods_img')
            ->join('goods','car.goods_id','=','goods.goods_id')
            ->where($where)
            ->whereIn('car.goods_id',$goods_id)
            ->get();
        return $res;
    }
}
