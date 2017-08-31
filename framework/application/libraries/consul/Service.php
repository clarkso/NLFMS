<?php

/**
 * 
 * @author clarkso
 *
 */
require 'Agent.php';
use consul\Agent as Agent;

class Service
{

    var $agent;

    public function __construct()
    {
        $this->agent = new Agent();
    }

    /**
     * 发现服务
     */
    public function discovery_service($service_name)
    {
        $services = $this->agent->getServices();
        $serv = json_decode($services);
        $service = $serv[$service_name];
        return $service;
    }

    /**
     * 注册服务
     * @param 
     */
    public function register_service($service_name, $url, $port = 80, $tags = NULL,  $health = NULL, $health_script = NULL)
    {
        $service = array(
            "ID" => $service_name,
            "Name" => $service_name,
            "Tags" => $tags,
            "Address" => $url,
            "Port" => $port,
            "EnableTagOverride" => false
        );
        if ($health !== NULL) {
            $check = array(
                "DeregisterCriticalServiceAfter" => "90m",
                "Script" => $health_script,
                "HTTP" => $health,
                "Interval" => "10s",
                "TTL" => "15s"
            );
            
            $service['check'] = $check;
        }
        
        return $this->agent->registerService(json_encode($service));
    }

    /**
     * 删除服务
     */
    public function remove_service($service_name)
    {}
}

?>