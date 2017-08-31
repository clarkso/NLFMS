<?php
/**
 * ZHIMA API: zhima.auth.engine.smsauth request
 *
 * @author auto create
 * @since 1.0, 2015-11-06 11:41:56
 */
class ZhimaAuthEngineSmsauthRequest
{
	/** 
	 * 商户传递的授权模式,不传则默认商户平台上配的一个authcode(多个会出错),传则校验是否在商户平台上配的authcodes中,用于支持多个authcode的情况
	 **/
	private $authCode;
	
	/** 
	 * 
	 **/
	private $identityParam;
	
	/** 
	 * 
	 **/
	private $identityType;

	private $apiParas = array();
	private $apiVersion="1.0";
	private $scene;
	private $channel;
	private $platform;
	private $extParams;

	
	public function setAuthCode($authCode)
	{
		$this->authCode = $authCode;
		$this->apiParas["auth_code"] = $authCode;
	}

	public function getAuthCode()
	{
		return $this->authCode;
	}

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
		return "zhima.auth.engine.smsauth";
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
