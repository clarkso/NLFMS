<?php

require_once 'short_message.php';

if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class SMS_CN_short_message implements Short_message
{

    private  $USERNAME = "";

    private  $PASSWD = "";

    private  $URL = "";


    //public function __construct(){

    //}

    /**
     * 
     * @param unknown $username
     * @param unknown $passwd
     * @param unknown $url
     */
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

        $msg = explode("&", $returns);
        $type = $msg[0];
        $status = substr($msg[1], strpos($msg[1], "=") + 1);
        $return_msg = substr($msg[2], strpos($msg[2], "=") + 1);

        return $type."&".$status."&".$msg;
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
}
