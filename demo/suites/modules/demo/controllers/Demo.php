<?php
// if (! defined ( 'BASEPATH' ))
// 	exit ( 'No direct script access allowed' );

// 	require_once (dirname ( __FILE__ ) . '/../config/configurations.php');
/**
 * CRM进入控制器类
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
class Demo extends NLF_Controller {
	/**
	 * 构造函数
	 *
	 * @access public
	 * @return void
	 */


	public function __construct() {
		parent::__construct ();
		$this->load->library('unit_test');

	}
	
	public function index(){
	    $this->load->view("welcome_view");
	}
	
	public function jsonrpc(){
	    $this->load->library("MicroService","","ms");
	    $obj = $this->ms->discovery_service("PersonServiceImpl");
	    echo $obj->getPerson("clark");
	}
	
}
?>