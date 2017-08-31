<?php
// namespace dubbox;
require_once "cluster.php";
require_once "invoker.php";
use dubbox\Cluster;
use dubbox\Invoker;

class Zookeeper_manager
{

    public $config = array(
        'registry_address' => 'localhost:2181'
    );

    public $zookeeper = null;

    protected $ip;

    protected $providersCluster;

    public static $ServiceMap = array();

    protected $acl = array(
        array(
            'perms' => \Zookeeper::PERM_ALL,
            'scheme' => 'world',
            'id' => 'anyone'
        )
    );

    private $params = array(
        "interface" => "",
        "application" => "",
        "generic" => "",
        "anyhost" => "false",
        "optimizer" => "",
        "owner" => "9thleaf",
        "side" => "provider",
        "timestamp" => ""
    );

    /**
     * 构造函数
     *
     * @param array $options            
     */
    public function __construct($options = array())
    {
        $this->config = array_merge($this->config, $options);
        $this->ip = $_SERVER['SERVER_ADDR'];
        $this->providersCluster = Cluster::getInstance();
        $CI = &get_instance();
        //print_r($CI->config->item('micro_service_uri'));
        $this->zookeeper = $this->getZookeeper($CI->config->item('micro_service_uri'));
    }

    /**
     * 获取zookeeper实例
     *
     * @param unknown $registry_address            
     */
    public function getZookeeper($registry_address)
    {
        return new \Zookeeper($registry_address);
    }

    /**
     * 订阅功能
     * 访问提供者路径
     *
     * @param unknown $invokDesc            
     */
    public function subscribe($invokDesc)
    {
        $desc = $invokDesc->toString();
        $serviceName = $invokDesc->getService();
        
        $path = $this->getSubscribePath($serviceName);
        $children = $this->zookeeper->getChildren($path);
        if (count($children) > 0) {
            foreach ($children as $key => $provider) {
                $provider = urldecode($provider);
                $this->methodChangeHandler($invokDesc, $provider);
            }
            $this->configurators();
        }
    }

    /**
     * 注册提供者
     * 
     * @param unknown $serviceName            
     * @param unknown $url            
     */
    public function register_provider($serviceName, $url)
    {
        $this->params['interface'] = $serviceName;
        $servicepath = "/dubbo/" . $serviceName;
        $subcribepath = $this->getSubscribePath($serviceName);
        $configpath = $this->getConfiguratorsPath($serviceName);
        $consumerpath = $this->getConsumersPath($serviceName);
        $routerpath = $this->getRoutersPath($serviceName);
        $providerpath = $this->getSubscribePath($serviceName) . "/" . urlencode($url . "?" . http_build_query($this->params));
        // 注册到zookeeper
        if (! $this->zookeeper->exists("/dubbo")) {
            $this->zookeeper->create("/dubbo", null, $this->acl, null);
        }
        if (! $this->zookeeper->exists($servicepath)) {
            $this->zookeeper->create($servicepath, null, $this->acl, null);
            $this->zookeeper->create($subcribepath, null, $this->acl, null);
            $this->zookeeper->create($providerpath, null, $this->acl, null);
            $this->zookeeper->create($configpath, null, $this->acl, null);
            $this->zookeeper->create($consumerpath, null, $this->acl, null);
            $this->zookeeper->create($routerpath, null, $this->acl, null);
        } else {
            if (! $this->zookeeper->exists($subcribepath)) {
                $this->zookeeper->create($subcribepath, null, $this->acl, null);
                $this->zookeeper->create($providerpath, null, $this->acl, null);
            } else {
                if (! $this->zookeeper->exists($providerpath)) {
                    $this->zookeeper->create($providerpath, null, $this->acl, null);
                }
            }
            if (! $this->zookeeper->exists($configpath)) {
                $this->zookeeper->create($configpath, null, $this->acl, null);
            }
            if (! $this->zookeeper->exists($consumerpath)) {
                $this->zookeeper->create($consumerpath, null, $this->acl, null);
            }
            if (! $this->zookeeper->exists($routerpath)) {
                $this->zookeeper->create($routerpath, null, $this->acl, null);
            }
        }
    }

    /**
     * Conumer注册功能
     *
     * @param unknown $invokDesc            
     * @param unknown $invoker            
     * @return boolean
     */
    public function register($invokDesc, $invoker)
    {
        $desc = $invokDesc->toString();
        if (! array_key_exists($desc, static::$ServiceMap)) {
            static::$ServiceMap[$desc] = $invoker;
        }
        // 订阅提供者
        $this->subscribe($invokDesc);
        // 获取提供者
        $providerHost = $this->providersCluster->getProvider($invokDesc);
        // 设置调用Host
        $invoker->setHost(Invoker::genDubboUrl($providerHost, $invokDesc));
        // print_r($invokDesc->getService() . "<br/>");
        // 获取注册路径
        $registerPath = $this->getRegistryPath($invokDesc->getService());
        
        // error_log($registerPath);
        // 注册到zookeeper
        if (! $this->zookeeper->exists($registerPath)) {
            $this->zookeeper->create($registerPath, null, $this->acl, null);
        }
        return true;
    }

    /**
     * 方法修改
     *
     * @param unknown $invokerDesc            
     * @param unknown $provider            
     */
    public function methodChangeHandler($invokerDesc, $provider)
    {
        $schemeInfo = parse_url($provider);
        $providerConfig = array();
        parse_str($schemeInfo['query'], $providerConfig);
        
        $group = isset($providerConfig['group']) ? $providerConfig['group'] : NULL;
        $version = isset($providerConfig['version']) ? $providerConfig['version'] : NULL;
        // print_r($schemeInfo['path']);
        if ($invokerDesc->isMatch($group, $version)) {
            $this->providersCluster->addProvider($invokerDesc, $schemeInfo['scheme'] . '://' . $schemeInfo['host'] . (isset($schemeInfo['port']) ? ':' . $schemeInfo['port'] : "") . $schemeInfo['path'], $schemeInfo['scheme']);
        }
    }

    /**
     * 获取调用
     *
     * @param unknown $invokerDesc            
     */
    public function getInvoker($invokerDesc)
    {
        $desc = $invokerDesc->toString();
        $all = static::$ServiceMap;
        return isset(static::$ServiceMap[$desc]) ? static::$ServiceMap[$desc] : NULL;
    }

    /**
     * 配置者
     *
     * @return boolean
     */
    public function configurators()
    {
        return true;
    }

    /**
     * 订阅路径
     *
     * @param unknown $serviceName            
     * @return string
     */
    protected function getSubscribePath($serviceName)
    {
        return '/dubbo/' . $serviceName . '/providers';
    }

    /**
     * 获取注册地址
     */
    protected function getRegistryAddress()
    {
        return $this->config['registry_address'];
    }

    /**
     * 获取注册路径
     *
     * @param unknown $serviceName            
     * @param array $application            
     */
    protected function getRegistryPath($serviceName, $application = array())
    {
        $params = http_build_query($application);
        $url = '/dubbo/' . $serviceName . '/consumers/' . urlencode('consumer://' . $this->ip . '/' . $serviceName . '?category=consumers&interface=' . $serviceName . $params);
        return $url;
    }

    /**
     * 获取配置路径
     *
     * @param unknown $serviceName            
     */
    protected function getConfiguratorsPath($serviceName)
    {
        return '/dubbo/' . $serviceName . '/configurators';
    }

    protected function getConsumersPath($serviceName)
    {
        return '/dubbo/' . $serviceName . '/consumers';
    }

    protected function getRoutersPath($serviceName)
    {
        return '/dubbo/' . $serviceName . '/routers';
    }

    /**
     * 设置提供者超时
     */
    protected function getProviderTimeout()
    {
        return $this->config['providerTimeout'] * 1000;
    }

    /**
     * zookeeper信息
     *
     * @param unknown $invokerDesc            
     */
    public function zkinfo($invokerDesc)
    {
        echo $this->getRegistryPath($invokerDesc->getService());
        var_dump($this->providersCluster->getProviders());
        var_dump($this->providersCluster);
    }

    /**
     * 删除一个节点
     *
     * @param string $path
     *            路径
     */
    public function deleteNode($path)
    {
        if (! $this->zookeeper->exists($path)) {
            return null;
        } else {
            return $this->zookeeper->delete($path);
        }
    }

    public function getNode($path)
    {
        return $this->zookeeper->getChildren($path);
    }

    public function getData($path)
    {
        return $this->zookeeper->get($path);
    }

    public function set_value($path, $value)
    {
        return $this->zookeeper->set($path, $value);
    }
}

?>