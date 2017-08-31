<?php

include(dirname ( __FILE__ ) . '/ZmopClient.php');
include(dirname ( __FILE__ ) . '/../ZmopSdk.php');

class TestIVSDetailGet_demo {
    //芝麻开放平台网关地址
    public $gatewayUrl = "https://zmopenapi.zmxy.com.cn/openapi.do";
    //商户私钥文件
    public $privateKeyFile = "../key/rsa_private_key.pem";
    //芝麻公钥文件
    public $zmPublicKeyFile =  "../key/rsa_public_key.pem";
    //数据编码格式
    public $charset = "UTF-8";
    //芝麻分配给商户的 appId
    public $appId = "1000978";

    public function testIVSDetailGet($name,$identify_id,$mobile,$transactionId){
        $client = new ZmopClient($this->gatewayUrl,$this->appId,$this->charset,dirname ( __FILE__ ).'/'.$this->privateKeyFile,dirname ( __FILE__ ).'/'.$this->zmPublicKeyFile);
        $request = new ZhimaCreditIvsDetailGetRequest();
        $request->setChannel("apppc");
        $request->setPlatform("zmop");
        $request->setProductCode("w1010100000000000103");  //必要参数，IVS的产品码是固定的，无需修改  
        $request->setTransactionId($transactionId);  //必要参数，业务流水号
        $request->setCertNo($identify_id);  //证件号、姓名、手机号、地址、银行卡、电子邮箱至少传其中两项  
        $request->setCertType("100");
        $request->setName($name);
        $request->setMobile($mobile);
//         $request->setEmail("jnlxhy@alitest.com");
//         $request->setBankCard ("6212263602057112062");
//         $request->setAddress("杭州市西湖区天目山路266号");
//         $request->setIp("101.247.161.1");
//         $request->setMac("44-45-53-54-00-00");
//         $request->setWifimac("00-00-00-00-00-00-00-E0");
//         $request->setImei("868331011992179");
//         $request->setImsi("460030091733165");
        //;//必要参数，授权获得的openid
        $response = $client->execute($request);
        return $response;
    }
}

