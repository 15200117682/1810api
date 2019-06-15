<?php

namespace App\Http\Controllers\Test;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    //
    public function curl2(){
        $url="https://www.baidu.com";
        //初始化
        $ch=curl_init($url);
        //设置参数
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,0);
        //执行会话
        curl_exec($ch);
        //关闭会话+6
        curl_close($ch);
    }

    public function getAccessToken(){
        $appid="wx3b19791119b8d948";
        $appsecret="bd5d85c36778d2779145850da0d1a9ee";
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
        //初始化
        $ch=curl_init($url);
        //设置参数
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        //执行会话
        $data=curl_exec($ch);
        $data=json_decode($data,true);
        //关闭会话
        curl_close($ch);
        return $data;
    }

    public function index(){
        return view('index.index');
    }
    public function curl3(){
        $name=$_POST['name'];
        $pwd=$_POST['pwd'];
        $url="http://vm.1810lument.com/test/curl3";
        $post_data=['name'=>$name,'pwd'=>$pwd];
        $post_data=json_encode($post_data,true);
        //初始化
        $ch=curl_init($url);
        //设置参数
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,0);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post_data);
        //执行会话
        curl_exec($ch);
        //关闭会话
        curl_close($ch);
    }

    //自定义菜单
    public function menu()
    {
        $access=$this->getAccessToken();
        $url='https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $access['access_token'];

        //初始化
        $ch = curl_init($url);
        $postData=[
            "button"=>[
                [
                    "type"=>"click",
                    "name"=>"今日话题",
                    "key"=>"ClICK"
                ]
            ]
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);
        //设置参数
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,0);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
        //执行会话
        curl_exec($ch);
        //关闭会话
        curl_close($ch);
    }




    function curl_request($url, $type = "GET", $data = '')
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
        curl_setopt($ch,CURLOPT_HEADER,0);

        $type = strtolower($type);
        switch ($type){
            case 'get':
                break;
            case 'post':
                //post请求配置
                curl_setopt($ch, CURLOPT_POST,1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    //对称加密
    public function encryption(Request $request){
        $string="123456abc";
        $key ="yuanfen";
        $iv="rfdefgvcsxewqjgh";
        $data=openssl_encrypt($string,"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv);

        $url="http://vm.1810lument.com/test/encryption";
        $client=new Client();
        $res = $client->request('POST',$url, [
            'body' => $data
        ]);
        $data=$res->getBody();
        return $data;
    }

    //非对称加密
    public function noEncryption(){
        $string="我爱添雯，因为他是个傻13";//数据
        $rsa_data=openssl_get_privatekey('file://'.storage_path('soft/rsa_private.pem'));//获取私钥位置
        openssl_private_encrypt($string,$enc_data,$rsa_data);//加密
        //签名
        $client=new Client();//new guzzle
        $url='http://vm.1810lument.com/test/no_enc';//url地址
        $res = $client->request('POST',$url, [
            'body' => $enc_data
        ]);//发送数据
        $data=$res->getBody();//接收返回的结果
        return $data;
    }

    //验签
    public function signature(){
        $data=[
            "user_id"=>123,
            "method"=>45,
            "add_id"=>23,
            "soft"=>"qwer"
        ];
        ksort($data);//排序
        $str0="";
        foreach ($data as $k=>$v){
            $str0.=$k."=".$v."&";
        }
        $str=rtrim($str0,"&");//去除最后的&符号
        $rsa_data=openssl_get_privatekey('file://'.storage_path('soft/rsa_private.pem'));//私钥位置
        openssl_sign($str,$key_sign,$rsa_data);//生成签名
        $sign=base64_encode($key_sign);//签名加密
        $data['sign']=$sign;//签名加入到数据中
        //echo "<pre>";print_r($data);echo "<pre>";exit;
        $url='http://vm.1810lument.com/test/signature?key=';//url地址
        $client=new Client();
        $res = $client->request('POST',$url, [
            'form_params' => $data
        ]);//发送数据
        $res=$res->getBody();//接收返回的结果
        echo $res;
    }

    //去支付
    public function alipay(){
        return view("pay.alipay");
    }

    //支付宝支付
    public function pay(){
        $order_data0=[
            "subject"       =>  "开心果",
            "out_trade_no"  =>  "1810-".mt_rand(1000,9999),
            "total_amount"  =>  mt_rand(1,10),
            "product_code"  =>  "QUICK_WAP_WAY"
        ];
        $order_data=json_encode($order_data0);
        $data=[
            "app_id"        =>  "2016091900549984",
            "method"        =>  "alipay.trade.wap.pay",
            "charset"       =>  "utf-8",
            "sign_type"     =>  "RSA2",
            "timestamp"     =>  date("Y-m-d h:i:s"),
            "version"       =>  "1.0",
            "biz_content"   =>  $order_data
        ];

        ksort($data);//排序
        $str0="";
        foreach ($data as $k=>$v){
            $str0.= $k."=".$v."&";
        }
        $str=rtrim($str0,"&");//去除最后的&符号

        $url = 'https://openapi.alipaydev.com/gateway.do';//手机端请求的地址

        $rsa_data=openssl_get_privatekey('file://'.storage_path('soft/rsa_private.pem'));//私钥位置
        //生成签名
        openssl_sign($str,$sign,$rsa_data,OPENSSL_ALGO_SHA256);
        $sign = base64_encode($sign);
        $data['sign']=$sign;

        $a='?';
        foreach($data as $key=>$val){
            $a.=$key.'='.urlencode($val).'&'; //用urlencode将字符串以url编码
        }
        $trim2 = rtrim($a,'&');
        $url2 = $url.$trim2;

        header('refresh:2;url='.$url2);

    }

}
