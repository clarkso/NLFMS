<?php

require_once 'short_message.php';

if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Ucpaas_short_message implements Short_message
{

    private  $USERNAME = "";

    private  $PASSWD = "";

    private  $URL = "";


    //public function __construct(){

    //}


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
    function send($phonenum, $content)
    {
        $signal = md5($this->PASSWD . $this->USERNAME);
        $messagenum = $phonenum . $this->getMillisecond();
        $url = $this->URL . "mtutf8/?uid=" . $this->USERNAME . "&pwd=" . $signal . "&mobile=" . $phonenum . "&mobileids=" . $messagenum . "&encode=utf8&content=" . $content;
        $returns = file_get_contents($url);
        return $returns;
        //return $returns;
    }

    public function get_status($phonenum)
    {
        $signal = md5($PASSWD . $USERNAME);
    }

    /**
     * 取剩余条数
     * @param unknown $phonenum
     * @param unknown $content
     */
    public function get_last_count($phonenum, $content)
    {
        $signal = md5($this->PASSWD . $this->USERNAME);
        $messagenum = $phonenum . getMillisecond();
        $url = $this->URL . "mm/?uid=" . $this->USERNAME . "&pwd=" . $signal . "&mobile=" . $phonenum . "&mobileids=" . $messagenum . "&content=" . $content;
        $returns = file_get_contents($url);

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
}
