<?php
/**
 * ZHIMA API: zhima.auth.engine.protocol request
 *
 * @author auto create
 * @since 1.0, 2015-11-02 16:17:16
 */
class ZhimaAuthEngineProtocolRequest
{
	/** 
	 * 授权的身份标识参数
	 **/
	private $identityParam;
	
	/** 
	 * 用户的身份标识类型\n3：身份证+姓名+手机号进行支付宝识别，核身，授权\n4：身份证+姓名+手机号（可选）进行公安网的核身，授权
	 **/
	private $identityType;

	private $apiParas = array();
	private $apiVersion="1.0";
	private $scene;
	private $channel;
	private $platform;
	private $extParams;

	
	public function setIdentityParam($identityParam)
	{
		$this->identityParam = $identityParam;
		$this->apiParas["identity_param"] = $identityParam;
	}

	public function getIdentityParam()
	{
		return $this->identityParam;
	}

	public function setIdentityType($identityType)
	{
		$this->identityType = $identityType;
		$this->apiParas["identity_type"] = $identityType;
	}

	public function getIdentityType()
	{
		return $this->identityType;
	}

	public function getApiMethodName()
	{
		return "zhima.auth.engine.protocol";
	}

	public function setScene($scene)
	{
		$this->scene=$scene;
	}

	public function getScene()
	{
		return $this->scene;
	}
	
	public function setChannel($channel)
	{
		$this->channel=$channel;
	}

	public function getChannel()
	{
		return $this->channel;
	}
	
	public function setPlatform($platform)
	{
		$this->platform=$platform;
	}

	public function getPlatform()
	{
		return $this->platform;
	}

	public function setExtParams($extParams)
	{
		$this->extParams=$extParams;
	}

	public function getExtParams()
	{
		return $this->extParams;
	}	

	public function getApiParas()
	{
		return $this->apiParas;
	}

	public function setApiVersion($apiVersion)
	{
		$this->apiVersion=$apiVersion;
	}

	public function getApiVersion()
	{
		return $this->apiVersion;
	}

}
