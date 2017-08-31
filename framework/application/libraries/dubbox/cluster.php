<?php
namespace dubbox;

/**
 * 集群类
 * @author clarkso
 * 根据zookeeper获取集群内的实例信息
 */
class Cluster{
    protected $providerMap = array();
    private static $_instance;

    /**
     * 构造函数
     */
    private function __construct(){

    }

    /**
     * 克隆函数
     */
    private function __clone(){

    }

    /**
     * 单例模式中的获取实例方法
     */
    public static function getInstance(){
        if(!(self::$_instance instanceof self)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 添加提供者
     * @param unknown $invokerDesc
     * @param unknown $host
     * @param unknown $schema
     */
    public function addProvider($invokerDesc,$host,$schema){
        $desc = $invokerDesc->toString();
        $this->providerMap[$desc][] = $host;
    }

    /**
     * 通过调用描述获取提供者
     * @param unknown $invokerDesc
     * @return NULL|mixed
     */
    public function getProvider($invokerDesc){
        $desc = $invokerDesc->toString();
        //print_r($this->providerMap);
        $returns = isset($this->providerMap[$desc])?$this->providerMap[$desc]:array();
        //var_dump($returns);
        $key = array_rand($returns);
        return isset($returns[$key])?$returns[$key]:NULL;
    }

    /**
     * 遍历所有提供者
     */
    public function getProviders(){
        return $this->providerMap;
    }
}