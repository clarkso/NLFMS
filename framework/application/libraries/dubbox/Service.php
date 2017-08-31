<?php

/**
 * 
 * @author clarkso
 *
 */
class Service{
    
    public $CI;
    public function __construct(){
        $this->CI = &get_instance();
    }
    
    /**
     * 发现服务
     * @param $service_name 服务名称
     * @return 返回远程调用服务对象
     */
    public function discovery_service($service_name){
        $this->CI->load->library('dubbox/Consumer', '', 'consumer');
        $method = $this->CI->consumer->call($service_name);
        return $method;
    }
    
    /**
     * 注册服务
     * 
     */
    public function register_service($service_name, $url, $port = 80, $tags = NULL,  $health = NULL, $health_script = NULL){
        $this->load->library('dubbox/Provider', '', 'provider');
        $this->provider->startService($service_name, $url);
    }
    
    /**
     * 删除服务
     */
    public function remove_service($service_name){
	    $this->load->library('dubbox/Zookeeper_manager', '', 'zkm');
	    //$method = $this->zkm->deleteNode('/dubbo/'.$_GET['servername'].'/providers/'.urlencode($_GET['node']));
	    //TODO: finish list service node and delete node 
        
    }
}

?>