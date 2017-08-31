<?php
/**
 * ZHIMA API: zhima.auth.info.authorize request
 *
 * @author auto create
 * @since 1.0, 2015-10-15 22:03:33
 */
class ZhimaAuthInfoAuthorizeRequest
{
	/** 
	 * 业务扩展字段，json字符串的key-value格式
	 **/
	private $bizParams;
	
	/** 
	 * 不同身份类型的参数列表，json字符串的key-value格式：\\n如：identityType =0\\nidentityParam ={&amp;ldquo;openId&amp;rdquo;:&amp;rdquo;268801234567890123456&amp;rdquo;}；\\n\\n如：identityType =1\\nidentityParam ={&amp;ldquo;mobileNo&amp;rdquo;:&amp;rdquo;13588888888&amp;rdquo;}
	 **/
	private $identityParam;
	
	/** 
	 * 身份标识类型（后续可以扩展）
0：芝麻信用开放账号ID
1：按照手机号进行授权
2: 按照身份证+姓名进行授权
	 **/
	private $identityType;

	//private $fileParas = array();
	private $apiParas = array();
	private $apiVersion="1.0";
	private $scene;
	private $channel;
	private $platform;
	private $extParams;

	
	public function setBizParams($bizParams)
	{
		$this->bizParams = $bizParams;
		$this->apiParas["biz_params"] = $bizParams;
	}

	public function getBizParams()
	{
		return $this->bizParams;
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
		return "zhima.auth.info.authorize";
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
	/*public function getFileParas()
	{
		return $this->fileParas;
	}*/

	public function setApiVersion($apiVersion)
	{
		$this->apiVersion=$apiVersion;
	}

	public function getApiVersion()
	{
		return $this->apiVersion;
	}

}
