<?php
require_once "Zookeeper_manager.php";
require_once dirname(dirname(__FILE__)) . "/jsonrpc/server/JsonRpcServer.php";
// include("../jsonrpc/server/JsonRpcServer.php");

// use "Zookeeper_manager";
class Provider
{

    protected $register;
    
    //------------------------------------amazing-split-line---------------------------------------------

    /**
     * 构造函数
     *
     * @param array $options
     */
    public function __construct($options = array())
    {
        $this->register = new Zookeeper_manager($options);
        // $this->register = new Register('localhost:2181');
    }
    
    //------------------------------------amazing-split-line---------------------------------------------

    /**
     * 注册到dubbox端
     *
     * @param string $serviceName
     * @param string $url
     */
    public function startService($serviceName, $url)
    {
        // 监测是否已经注册
        $this->register->register_provider($serviceName, $url);
        // 启动服务器
        $server = new JsonRpcServer(file_get_contents("php://input"));

        @require NONPHARPATH . "suites/modules/". SUITENAME . "/providers/". $serviceName . ".php";

        @$class = new ReflectionClass($serviceName);
        @$server->addService($class->newInstance());
        @$server->processingRequests();
    }
    
    //------------------------------------amazing-split-line---------------------------------------------
    
    /**
     * 获取服务
     * @param unknown $impl
     */
    public function getService($impl)
    {

        // 启动jsonrpc服务器
        $server = new JsonRpcServer(file_get_contents("php://input"));

        $server->addService($impl);
        $server->processingRequests();
    }
}

?>