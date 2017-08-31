<?php
/**
 * ZHIMA API: zhima.auth.face.verify request
 *
 * @author auto create
 * @since 1.0, 2015-11-17 10:46:25
 */
class ZhimaAuthFaceVerifyRequest
{
	/** 
	 * base64的字符集
	 **/
	private $images;
	
	/** 
	 * 标识一次请求流水
	 **/
	private $token;

	private $apiParas = array();
	private $apiVersion="1.0";
	private $scene;
	private $channel;
	private $platform;
	private $extParams;

	
	public function setImages($images)
	{
		$this->images = $images;
		$this->apiParas["images"] = $images;
	}

	public function getImages()
	{
		return $this->images;
	}

	public function setToken($token)
	{
		$this->token = $token;
		$this->apiParas["token"] = $token;
	}

	public function getToken()
	{
		return $this->token;
	}

	public function getApiMethodName()
	{
		return "zhima.auth.face.verify";
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
