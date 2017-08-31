<?php
require_once 'short_message.php';

if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Cloud_sms_short_message implements Short_message
{

    private $USERNAME = "";

    private $PASSWD = "";

    private $URL = "";

    public function __construct($username, $passwd, $url)
    {
        $this->USERNAME = $username;
        $this->PASSWD = $passwd;
        $this->URL = $url;
    }

    /**
     * 发送信息
     *
     * @param unknown $phonenum
     * @param unknown $content
     */
    function send($phonenum, $content, $rand_code = '')
    {
        // $signal = md5($this->PASSWD . $this->USERNAME);
        $messagenum = $this->getMillisecond();

        $url = $this->URL . "maap/sms/code?sid=" . $this->USERNAME . "&appId=1b46f3563dc64edba046a3681e35aefb&time=" . $messagenum . "&sign=" . MD5($this->USERNAME . $messagenum . "be247314bb8adcaecbe76c6171009780") . "&to=" . $phonenum . "&templateId=23557&param=" . $rand_code;
        $return_msg = file_get_contents($url);
        // error_log($returns);

        // 分析返回值
        //$returns = explode(',', $return_msg);
        error_log($return_msg);
        $returns = json_decode($return_msg);
        //print_r($returns);
        $type = 'sms';
        $status = $returns->resp->respCode;
        $msg = "";
        switch ($status) {
            case "000000":
                {
                    $msg = "发送成功";
                    break;
                }
        }

        return $type . "&" . $status . "&" . $msg;
        // return $returns;
    }

    public function get_status($phonenum)
    {
        // $signal = md5($PASSWD . $USERNAME);
    }

    /**
     * 取剩余条数
     *
     * @param unknown $phonenum
     * @param unknown $content
     */
    public function get_last_count($phonenum, $content)
    {
        /*
         * $signal = md5($this->PASSWD . $this->USERNAME);
         * $messagenum = $phonenum . getMillisecond();
         * $url = $this->URL . "mm/?uid=" . $this->USERNAME . "&pwd=" . $signal . "&mobile=" . $phonenum . "&mobileids=" . $messagenum . "&content=" . $content;
         * $returns = file_get_contents($url);
         */
    }

    /**
     * 获取毫秒
     *
     * @return number
     */
    function getMillisecond()
    {
        $datetime = date("YmdHis");
        list ($t1, $t2) = explode(' ', microtime());
        //return (float) sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
        return $datetime.substr($t2, 0, 3);
    }
}
