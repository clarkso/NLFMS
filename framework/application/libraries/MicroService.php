<?php
if (! defined ( 'BASEPATH' ))
    exit ( 'No direct script access allowed' );
/**
 * 微服务框架接口
 * 
 * @author clarkso
 *
 */
Class MicroService{
    
    private $CI;
    private $service_mode;
    private $service;
    
    /**
     * 构造函数
     * 
     * 首先获取使用的微服务发现框架
     * 
     */
    public function __construct(){
        
        $this->CI = &get_instance ();
        $this->config =& load_class('Config', 'core');
        $this->service_mode = $this->CI->config->item('micro_service_mode');
        require $this->service_mode.'/Service.php';
        $this->service = new Service();
    }
    
    /**
     * 发现服务
     */
    public function discovery_service($service_name){
        return $this->service->discovery_service($service_name);
    }
    
    /**
     * 注册服务
     * 
     */
    public function register_service($service_name, $url, $port = 80, $tags = NULL,  $health = NULL, $health_script = NULL){

        return $this->service->register_service($service_name, $url, $port, $tags, $health, $health_script);
    }
    
    /**
     * 删除服务
     */
    public function remove_service($service_name){
        return $this->service->remove_service($service_name);
    }
    
        
}