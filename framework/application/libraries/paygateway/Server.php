<?php
/*
 * 统一支付平台类
 */
class Server{
    
    private $partnerCode = '';      //商户业务代码：接入时由汇通宝提供
    private $accountName = '';     //帐户名
    private $pwd = '';             //密码
    private $pfx = '';  //公钥地址
    private $base_url = '';     //请求地址
    private $notifyUrl = '';  //后台回调地址
    private $returnUrl = '';  //前台回调地址
    
    public function __construct() {
    }
    
    public function bocwmpay($order_num,$balance,$pan='',$plan_id=''){
        $data = array();
        $param = array();
        $data['version'] = 'v2.0';
        $data['charset'] = 'UTF-8';
        $data['currency'] = 'CNY';
        $data['partnerCode'] = $this->partnerCode;
        $data['accountName'] = $this->accountName;
        $data['orderNo'] = $order_num;
        $data['orderTime'] = date('YmdHis',$_SERVER['REQUEST_TIME']);
        $data['amount'] = $balance;
        $data['signType'] = 1;
        $data['returnUrl'] = $this->returnUrl;     //前台回调地址
        $data['notifyUrl'] = $this->notifyUrl;       //后台回调地址
        $data['extend1'] = $pan;
        $data['extend1'] = $plan_id;
        $data['extend2'] = '';
        foreach($data as $k=>$v)
        {
            $param[] = "$k=$v";
        }
        $param = join('&',$param);
        $url = $this->base_url.$param. '&signContent='.urlencode($this->_getSignPKCS12($param));
        return $url;
    }
    
    
    /**
     * 生成pkc12签名	支付接口需要使用
     */
    private function _getSignPKCS12($msg)
    {
        return $this->_signByPkcs12($msg,$this->pfx,$this->pwd);
    }
    
    /**
     *使用pkcs12方式进行签名，并进行base64编码
     */
    private function _signByPkcs12($msg,$pfx,$pwd='')
    {
        $key=array();
        openssl_pkcs12_read(file_get_contents(dirname(__FILE__).'/'.$pfx),$key,$pwd);
        openssl_sign($msg, $sign, $key['pkey']);
        return base64_encode($sign);
    }
}