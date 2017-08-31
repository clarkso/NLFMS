<?php

require_once 'short_message.php';

if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class ZhongJing_short_message implements Short_message
{


	public $send = array(
				'channel'=>'HYTX',  //消息平台分配的应用ID
				'corporationCode'=>'eee',   //弃用，保持为空
				'businessName'=>'ddd'		//弃用，保持为空
		);

    private  $USERNAME = "";

    private  $PASSWD = "";

    private  $URL = "";


    public function __construct($username, $passwd, $url){

        $this->USERNAME = $username;
        $this->PASSWD = $passwd;
        $this->URL = $url;
    }

    /**
     * 发送信息
     * @param unknown $phonenum
     * @param unknown $content
     */
    function send($mobileStr, $content)
    {
		//配置中获取请求地址
		$url = $this->URL;
        //手机号字符串 多个手机号 以,符号隔开

		if(empty($mobileStr) || empty($content)){
            return false;
		}
		//数组转换
		$mobileArr = explode(',',$mobileStr);
        $user_mobile = array();
		//手机号认证处理
		foreach($mobileArr as $val){
			//去除空格
			$mobile = str_replace(' ', '',$val);
			//去除中划线-
			$mobile = preg_replace("/-/is","",$mobile);
			//截取字符串长度
			if(strlen($mobile)>11){
				$mobile = substr($mobile,strlen($mobile)-11,11);
			}
			//手机号码验证
			if (preg_match("/^1[34578]{1}\d{9}$/",$mobile)){
				$user_mobile[] = $mobile;
			}
		}

		if(count($user_mobile) == 0){
			return false;
		}

		$send = $this->send;
		$send["channel"] = $this->USERNAME;
		//按键值排序
		ksort($send);
		//生成签名
		$sign = $this->generateSign($send);
        $send['sign'] = $sign;
		$send['mobiles'] = implode(',',$user_mobile);
		$send['content'] = $content;
		//发送请求
		$result = $this->postCurl($url,$send);

		$result = json_decode($result,true);


	
		//echo 'sms'."&".$result["stateCode"]."&".$result["stateCodeDesc"];


		return 'sms'."&".$result["stateCode"]."&".$result["stateCodeDesc"];
        


    }

	public function postCurl($url,$data){
		$ch = curl_init();
		//设置选项，包括URL
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);  //定义超时3秒钟
		// POST数据
		curl_setopt($ch, CURLOPT_POST, 1);
		// 把post的变量加上
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		//执行curl
		$output = curl_exec($ch);
		//关闭curl
		curl_close($ch);

		return $output;
	}

    public function get_status($phonenum)
    {
        //$signal = md5($PASSWD . $USERNAME);
    }

    /**
     * 取剩余条数
     * @param unknown $phonenum
     * @param unknown $content
     */
    public function get_last_count($phonenum, $content)
    {
       /* $signal = md5($this->PASSWD . $this->USERNAME);
        $messagenum = $phonenum . getMillisecond();
        $url = $this->URL . "mm/?uid=" . $this->USERNAME . "&pwd=" . $signal . "&mobile=" . $phonenum . "&mobileids=" . $messagenum . "&content=" . $content;
        $returns = file_get_contents($url);
        */
    }

    /**
     * 获取毫秒
     *
     * @return number
     */
    function getMillisecond()
    {
        list ($t1, $t2) = explode(' ', microtime());
        return (float) sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
    }

    /**
     * 随机多位
     * @param $n 随机值大小
     */
    function random($n){
        $min = 1*pow(10, ($n - 1));
        $max = 1*pow(10, ($n)) - 1;
        return rand($min, $max);
    }

	//签名
	public function generateSign($send){
		 $sign = $this->PASSWD;
		 foreach($send as $key=>$val){
			 $sign.=$key;
			 $sign.=$val;
		 }

		 //md5加密
		 $sign = md5($sign);
		 //加密后转换为大写
		 $sign = strtoupper($sign);
		 //签名返回
		 return $sign;
	}
}
