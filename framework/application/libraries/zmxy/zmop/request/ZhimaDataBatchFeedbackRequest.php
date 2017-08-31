<?php
/**
 * ZHIMA API: zhima.data.batch.feedback request
 *
 * @author auto create
 * @since 1.0, 2015-11-20 10:51:38
 */
class ZhimaDataBatchFeedbackRequest
{
	/** 
	 * 
	 **/
	private $bizExtParams;
	
	/** 
	 * 
	 **/
	private $columns;
	
	/** 
	 * 
	 **/
	private $file;
	
	/** 
	 * 反馈文件的数据编码
	 **/
	private $fileCharset;
	
	/** 
	 * 
	 **/
	private $fileDescription;
	
	/** 
	 * 
	 **/
	private $fileType;
	
	/** 
	 * 
	 **/
	private $primaryKeyColumns;
	
	/** 
	 * 
	 **/
	private $records;
	
	/** 
	 * 
	 **/
	private $typeId;

	private $apiParas = array();
	private $fileParas = array();
	private $apiVersion="1.0";
	private $scene;
	private $channel;
	private $platform;
	private $extParams;

	
	public function setBizExtParams($bizExtParams)
	{
		$this->bizExtParams = $bizExtParams;
		$this->apiParas["biz_ext_params"] = $bizExtParams;
	}

	public function getBizExtParams()
	{
		return $this->bizExtParams;
	}

	public function setColumns($columns)
	{
		$this->columns = $columns;
		$this->apiParas["columns"] = $columns;
	}

	public function getColumns()
	{
		return $this->columns;
	}

	public function setFile($file)
	{
		$this->file = $file;
		$this->fileParas["file"] = $file;
	}

	public function getFile()
	{
		return $this->file;
	}

	public function setFileCharset($fileCharset)
	{
		$this->fileCharset = $fileCharset;
		$this->apiParas["file_charset"] = $fileCharset;
	}

	public function getFileCharset()
	{
		return $this->fileCharset;
	}

	public function setFileDescription($fileDescription)
	{
		$this->fileDescription = $fileDescription;
		$this->apiParas["file_description"] = $fileDescription;
	}

	public function getFileDescription()
	{
		return $this->fileDescription;
	}

	public function setFileType($fileType)
	{
		$this->fileType = $fileType;
		$this->apiParas["file_type"] = $fileType;
	}

	public function getFileType()
	{
		return $this->fileType;
	}

	public function setPrimaryKeyColumns($primaryKeyColumns)
	{
		$this->primaryKeyColumns = $primaryKeyColumns;
		$this->apiParas["primary_key_columns"] = $primaryKeyColumns;
	}

	public function getPrimaryKeyColumns()
	{
		return $this->primaryKeyColumns;
	}

	public function setRecords($records)
	{
		$this->records = $records;
		$this->apiParas["records"] = $records;
	}

	public function getRecords()
	{
		return $this->records;
	}

	public function setTypeId($typeId)
	{
		$this->typeId = $typeId;
		$this->apiParas["type_id"] = $typeId;
	}

	public function getTypeId()
	{
		return $this->typeId;
	}

	public function getApiMethodName()
	{
		return "zhima.data.batch.feedback";
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
	
	public function getFileParas()
	{
		return $this->fileParas;
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
