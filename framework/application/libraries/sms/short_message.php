<?php
interface Short_message
{
    public function __construct($username, $passwd, $url);
    public function send($phonenum, $content);
    public function get_status($phonenum);
    public function get_last_count($phonenum, $content);
}
?>