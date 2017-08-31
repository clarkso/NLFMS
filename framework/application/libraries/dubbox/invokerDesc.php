<?php
namespace dubbox;

/**
 * 调用实现
 *
 * @author clarkso
 *
 */
class invokerDesc
{

    private $serviceName = " ";

    private $group = " ";

    private $version = " ";

    private $schema = 'jsonrpc';

    /**
     * 构造函数
     * @param unknown $serviceName
     * @param unknown $version
     * @param unknown $group
     */
    public function __construct($serviceName, $version = null, $group = null)
    {
        $this->serviceName = $serviceName;
        $this->version = $version;
        $this->group = $group;
    }

    /**
     * 获取服务
     */
    public function getService()
    {
        return $this->serviceName;
    }

    /**
     * 字符串化
     * @return string
     */
    public function toString()
    {
        $group_str = isset($this->group) ? $this->group : ' ';
        $version_str = isset($this->version) ? $this->version : ' ';
        return $this->serviceName . '_' . $group_str . '_' . $version_str . '_' . $this->schema;
    }

    /**
     * 是否匹配
     * @param unknown $group
     * @param unknown $version
     */
    public function isMatch($group, $version)
    {
        return $this->group === $group && $this->version === $version;
    }

    /**
     * 信息是否敏合
     * @param unknown $desc
     */
    public function isMatchDesc($desc)
    {
        return $this->group == $desc->group && $this->version == $desc->version;
    }
}

?>