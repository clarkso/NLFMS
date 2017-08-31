<?php
require_once (dirname ( __FILE__ ) . '/../../../application/libraries/zmxy/zmop/control.php');

if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class ZMXY extends TestIVSDetailGet_demo
{
    function check_real_name($name,$identify_id,$mobile){
        //生成流水号
        $transactionId = date('YmdHisu').mt_rand(0, 9999999999);
        
        $test = new TestIVSDetailGet_demo();
        return $test->testIVSDetailGet($name,$identify_id,$mobile,$transactionId);
    }
}

