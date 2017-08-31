<?php
// if (! defined ( 'BASEPATH' ))
// 	exit ( 'No direct script access allowed' );

// 	require_once (dirname ( __FILE__ ) . '/../config/configurations.php');

require_once (APPPATH."libraries/jsonrpc/client/JsonRpcClient.php");
/**
 * jsonrpc测试控制器类
 *
 * @package
 *
 * @subpackage core
 * @category core
 * @author
 *
 * @link
 *
 */
class Test_jsonrpc extends Api_Controller {
	/**
	 * 构造函数
	 *
	 * @access public
	 * @return void
	 */

	public function __construct() {
		parent::__construct ();
	}

	/**
	 * 注册服务器
	 */
	public function Register_server() {
	   $this->load->library('jsonrpc/server/JsonRpcServer',array('postRequest'=>'http://192.168.10.139/'));
	}

	/**
	 * 注册客户端
	 */
	public function Register_client(){
	   $this->load->library('jsonrpc/client/JsonRpcClient');
	}

	public function test_zookeeper(){
	   $this->load->library('dubbox/Zookeeper_manager', '', 'zkm');
	   $invokerDesc = $this->zkm->new_invoker();
       $this->zkm->zkinfo($invokerDesc);
	}

	
	/**
	 * 获取消费者
	 */
	public function get_consumer(){
        $this->load->library('unit_test');
	    $this->load->library('dubbox/Consumer', '', 'consumer');
	    $method = $this->consumer->call("PersonServiceImpl");
	    //print_r("Test:");
	    $result = $method->getPersons();
        print_r($result);
	    //$this->unit->run($method->getPersons());
	    //var_dump($method->getPersons());
	    //echo $this->unit->report();
	}

	/**
	 * 获取消费者
	 */
	public function get_person(){
        $this->load->library('unit_test');
	    $this->load->library('dubbox/Consumer', '', 'consumer');
	    $method = $this->consumer->call("PersonServiceImpl");
// 	    print_r("Test:");
	    $result = $method->getPerson("test");         //调用对象了方法，即PersonServiceImpl类文件里的方法
        print_r($result);
	    $this->unit->run($method->getPerson('test'));
	    //var_dump($method->getPersons());
// 	    echo $this->unit->report();
	}

	
	/**
	 * 注册提供者
	 */
	public function reg_provider(){
	    $this->load->library('dubbox/Provider', '', 'provider');
	    $this->provider->startService('sendSMS', 'http://cecrm.9-leaf.com/index.php/_api/Usercenter/reg_provider');
	}

	public function unit_reg_provider(){
        $this->load->library('unit_test');
	    $this->load->library('dubbox/Zookeeper_manager', '', 'zkm');
	    $result = $this->zkm->deleteNode("/dubbo/PersonServiceImpl/providers/http%3A%2F%2F192.168.10.139%2Fcecrm%2Findex.php%2F_api%2Ftest_jsonrpc%2Freg_provider");
	    $this->load->library('dubbox/Provider', '', 'provider');
	    $this->unit->run($this->provider->startService("PersonServiceImpl", site_url("_api/test_jsonrpc/reg_provider")));
// 	    echo $this->unit->report();
	}
	
	public function list_provider(){
	    $this->load->library('dubbox/Zookeeper_manager', '', 'zkm');
	    $method = $this->zkm->getNode($_GET['path']);
	    foreach ($method as $method1){
	        
	        print_r($method1);
// 	        $del = $this->zkm->deleteNode("/dubbo/PersonServiceImpl/".$method1);
// 	        print_r($del);
	        echo "<br />";
	    }
	}
	
	public function delete_provider(){
	    $this->load->library('dubbox/Zookeeper_manager', '', 'zkm');
	    $method = $this->zkm->deleteNode('/dubbo/'.$_GET['servername'].'/providers/'.urlencode($_GET['node']));
	    var_dump($method);
	}
	
	
	public function create(){
	    
	    $this->load->library('dubbox/Zookeeper_manager', '', 'zkm');
	    $data = $this->zkm->register_provider("Test7",site_url("_api/Usercenter_view"));
	}
	
	public function set_value(){
	    $this->load->library('dubbox/Zookeeper_manager','','zkm');
	    $data = $this->zkm->set_value($_GET['path'],$_GET['value']);
	    var_dump($data);
	}
	
	public function getData(){
	    $this->load->library('dubbox/Zookeeper_manager','','zkm');
	    $data = $this->zkm->getData('/');
	    var_dump($data);
	}
	
	public function delete_consumer(){
	    $this->load->library('dubbox/Zookeeper_manager', '', 'zkm');
	    $method = $this->zkm->deleteNode('/dubbo/'.$_GET['servername'].'/consumers/'.urlencode($_GET['node']));
	    var_dump($method);
	    
	}
	
	public function delete_node(){
	    $this->load->library('dubbox/Zookeeper_manager', '', 'zkm');
	    $method = $this->zkm->deleteNode($_GET['path']);
	    var_dump($method);
	}
	
// 	public function delete_server($server){
// 	    $this->load->library('dubbox/Zookeeper_manager','','zkm');
// 	    $node = $this->zkm->gerNode('/dubbo/'.$server);
// 	    if ($node){
// 	    foreach ($node as $re){
// 	        $this->delete_server('/dubbo/'.$server.'/'.$re);
// 	    }
// 	    }else{
// 	        $this->delete_node()
// 	    }
// 	}

	public function request_for_provider(){
	    /*
	     *jsonrpc请求
	     */
	    $ch = curl_init();
	    // $params = array('name','jack');
	    $params = array('name'=>'jack');
	    if ($params){
	        $params = json_encode($params);
	    }
	    $data = json_encode(array('jsonrpc'=>'2.0','id'=>'1','method'=>'get_account','params'=>$params));
	    $userpwd = "testclient:testpass";
	    curl_setopt($ch, CURLOPT_URL, 'http://192.168.10.139/cecrm/index.php/_api/Test_jsonrpc/reg_provider');
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true); //  PHP 5.6.0 后必须开启
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	    // curl_setopt($ch, CURLOPT_USERPWD, $userpwd);
	    
	    curl_exec($ch);
	    curl_close($ch);
	}
	
	//---------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * 提供接口
	 */
	
	//根据id获取用户信息
	//testlink:http://192.168.10.139/cecrm/index.php/_api/Test_jsonrpc/getUserById/1
	public function getUserById(){
	    //获取参数
	    $id = $this->input->get_post("id");
	    
	    $this->load->library('dubbox/Consumer','','consumer');
	    $method = $this->consumer->call('Test7');
	    $result = $method->get_account($id);
	    echo $result;
	}
	
	/*
	 * 登录
	 * @param mobile string 用户账号，即手机号
	 * @param password string 密码sha1加密
	 */
	//testlink:http://192.168.10.139/cecrm/index.php/_api/Test_jsonrpc/login/13794369174/3D4F2BF07DC1BE38B20CD6E46949A1071F9D0E3D
	public function login(){
	    //获取参数
	    $mobile = $this->input->get_post('mobile');
	    $password = $this->input->get_post('password');
	    
	    $this->load->library('dubbox/Consumer','','consumer');
	    $method = $this->consumer->call('Test7');
	    $result = $method->login($mobile,$password);
	    echo $result;
	}
	
	/*
	 * 更新用户资料，更新user_info表
	 * @param id int 用户id
	 * @param params array 更新内容，可更新内容待定
	 */
	public function updateUserInfo(){
	    //获取参数
	    $id = $this->input->get_post("id");
	    $params = $this->input->get_post("params");
	    
	    $this->load->library('dubbox/Consumer','','consumer');
	    $method = $this->consumer->call('Test7');
	    $result = $method->update_account_info($id,$params);
	    echo $result;
	}
	
	/*
	 *  更新用户V值，更新user_info表
	 * 
	 */
	public function updateUserValue(){
	    //获取参数
	    $id = $this->input->get_post("id");
	    $grade = $this->input->get_post("grade");
	    $value = $this->input->get_post("value");
	    
	    $this->load->library('dubbox/Consumer','','consumer');
	    $method = $this->consumer->call('Test7');
	    $result = $method->update_value($id,$grade,$value);
	    echo $result;
	}
	
	/*
	 * 更新用户积分，更新user_info表
	 */
	public function updateUserScore(){
	    //获取参数
	    $id = $this->input->get_post("id");
	    $score = $this->input->get_post("score");
	    
	    $this->load->library('dubbox/Consumer','','consumer');
	    $method = $this->consumer->call('Test7');
	    $result = $method->update_score($id,$score);
	    echo $result;
	}
	
	/*
	 * 分页获取用户
	 * @param search array 搜索条件，可用参数待定、
	 * @param page int 当前页
	 * @param perpage int 每页显示条数
	 */
	public function getUserList(){
	    //获取参数
	    $search = $this->input->get_post("search");
	    $page = $this->input->get_post("page");
	    $perpage = $this->input->get_post("perpage");
	    
	    $this->load->library('dubbox/Consumer','','consumer');
	    $method = $this->consumer->call('Test7');
	    $result = $method->get_user_list($search,$page?$page:1,$perpage?$perpage:10);
	    echo $result;
	}
	
	/*
	 * 记录经纬度
	 * @param id int 用户id
	 * @param longitude float 经度
	 * @param latitude float 纬度
	 */
	public function recordLocation(){
	    //获取参数
	    $id = $this->input->get_post("id");
	    $longitude = $this->input->get_post("longitude");
	    $latitude = $this->input->get_post("latitude");
	    
	    $this->load->library('dubbox/Consumer','','consumer');
	    $method = $this->consumer->call('Test7');
	    $result = $method->record_location($id,$longitude,$latitude);
	    echo $result;
	}
	
	/*
	 * 注册
	 */
	public function register(){
	    //获取参数
	    $mobile = (int)$this->input->get_post('mobile');
	    $md5password = $this->input->get_post("md5password");
	    $sha1password = $this->input->get_post("sha1password");
	    $type = (int)$this->input->get_post("type");
	    $num = $this->input->get_post("num");
	    $nick_name = $this->input->get_post("nick_name");
	    $avatar = $this->input->get_post("avatar");
	    
	    $this->load->library('dubbox/Consumer','','consumer');
	    $method = $this->consumer->call('Test7');
	    $result = $method->register($mobile,$md5password,$sha1password,$type,$num,$nick_name,$avatar);
	    echo $result;
	}
	
	/*
	 * 冻结/解冻
	 */
	public function changeLockStatus(){
	    //获取参数
	    $id = $this->input->get_post("id");
	    $status = (int)$this->input->get_post("status");
	    
	    $this->load->library('dubbox/Consumer','','consumer');
	    $method = $this->consumer->call('Test7');
	    $result = $method->change_lock_statues($id,$status);
	    echo $result;
	}
	
	/*
	 * 修改密码
	 */
	public function updatePassword(){
	    //获取参数
	    $mobile = (int)$this->input->get_post('mobile');
	    $md5password = $this->input->get_post("md5password");
	    $sha1password = $this->input->get_post("sha1password");
	    
	    $this->load->library('dubbox/Consumer','','consumer');
	    $method = $this->consumer->call('Test7');
	    $result = $method->update_password($mobile,$md5password,$sha1password);
	    echo $result;
	}
	
	/*
	 * 绑定第三方
	 */
	public function bundlingThirdParty(){
	    //获取参数
	    $id = $this->input->get_post("id");
	    $type = (int)$this->input->get_post("type");
	    $num = $this->input->get_post("num");
	    $nick_name = $this->input->get_post("nick_name");
	    $avatar = $this->input->get_post("avatar");
	    
	    $this->load->library('dubbox/Consumer','','consumer');
	    $method = $this->consumer->call('Test7');
	    $result = $method->bundling_third_party($id,$type,$num,$nick_name,$avatar);
	    echo $result;
	}
	
	/*
	 * 解绑第三方
	 */
	public function unbundlingThirdParty(){
	    //获取参数
	    $id = $this->input->get_post("id");
	    $type = (int)$this->input->get_post("type");
	    
	    $this->load->library('dubbox/Consumer','','consumer');
	    $method = $this->consumer->call('Test7');
	    $result = $method->unbundling_third_party(urldecode($id),$type);
	    echo $result;
	}
	

	public function fromUserBase(){
	    $this->load->library('dubbox/Consumer','','consumer');
	    $method = $this->consumer->call('SynchronousData');
	    $result = $method->from_user_base();
	    echo $result;
	}
	public function fromUserInfo(){
	    $this->load->library('dubbox/Consumer','','consumer');
	    $method = $this->consumer->call('SynchronousData');
	    $result = $method->from_user_info();
	    echo $result;
	}
	
	public function curl_reg11(){
	    $ch = curl_init();
	    // $params = array('name','jack');
	    $params = array();
	    if ($params){
	        $params = json_encode($params);
	    }
	    $data = json_encode(array('jsonrpc'=>'2.0','id'=>'1','method'=>'get_account','params'=>$params));
	    $userpwd = "testclient:testpass";
	    curl_setopt($ch, CURLOPT_URL, 'http://cecrm.9-leaf.com/index.php/_api/Test_jsonrpc/reg_provider');
// 	    curl_setopt($ch, CURLOPT_URL, 'http://192.168.10.139/cecrm/index.php/_api/test_jsonrpc/reg_provider');
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true); //  PHP 5.6.0 后必须开启
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	    // curl_setopt($ch, CURLOPT_USERPWD, $userpwd);
	    
	    curl_exec($ch);
	    curl_close($ch);
	    exit;
	}
}
?>