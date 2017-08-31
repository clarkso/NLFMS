<?php
require_once "Zookeeper_manager.php";
require_once "invoker.php";
require_once "invokerDesc.php";
require_once "protocols/jsonrpc.php";
//include ("../jsonrpc/client/JsonRpcClient.php");

use dubbox\invoker;
use dubbox\invokerDesc;
use dubbox\protocols\jsonrpc;

/**
 * 消费者类
 *
 * @author clarkso
 *
 */
class Consumer
{
    protected $register;
    protected $protocols = array();

    /**
     * 构造函数
     */
    public function __construct($options = array())
    {
        $this->register = new Zookeeper_manager($options);
    }

    /**
     * 召唤方法
     *
     * @param unknown $method
     */
    public function call($serviceName, $version = NULL, $group = NULL, $protocol = "jsonrpc")
    {
        $url = "";
        //向dubbox服务器获取对象
        $invokerDesc = new InvokerDesc($serviceName, $version, $group);
        $this->register->subscribe($invokerDesc);
        $invoker = $this->register->getInvoker($invokerDesc);
        //echo $invoker->toString();
        if (! $invoker) {                       //对象不存在，则执行注册
            // $invoker = new jsonrpc();
            $invoker = $this->getInvokerByProtocol($protocol);
            $this->register->register($invokerDesc, $invoker);
        }
        return $invoker;
    }

    /**
     * 根据协议获取
     * @param unknown $protocol
     * @throws \Exception
     */
    private function getInvokerByProtocol($protocol){       

        if($this->protocols == null && !in_array($protocol, $this->protocols)){
            foreach( glob( "protocols/*.php" ) as $filename ){
                $protoName = basename($filename,".php");
                array_push($this->protocols, $protoName);
                require_once $filename;
            }
        }

        if(class_exists("dubbox\protocols\\$protocol")){
            $class =  new \ReflectionClass("dubbox\protocols\\$protocol");
            $invoker = $class->newInstanceArgs(array());
            return $invoker;
        }else{
            throw new \Exception("can't match the class according to this protocol $protocol");
        }
    }
}

?>