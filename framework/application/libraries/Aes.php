<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Aes
{
	private $iv = 'fedcba9876543210'; #Same as in JAVA
	private $key = '9thleaf9thleaf11'; #Same as in JAVA


	public function __construct()
	{
		$this->CI = &get_instance ();
	}

	public function Encrypt($str) {

	  //$key = $this->hex2bin($key);    
	  $iv = $this->iv;

	  $td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);

	  mcrypt_generic_init($td, $this->key, $iv);
	  $encrypted = mcrypt_generic($td, $str);

	  mcrypt_generic_deinit($td);
	  mcrypt_module_close($td);

	  return bin2hex($encrypted);
	}

	public function Decrypt($code) {
	  //$key = $this->hex2bin($key);
	  $code = $this->Hex2bin($code);
	  $iv = $this->iv;

	  $td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);

	  mcrypt_generic_init($td, $this->key, $iv);
	  $decrypted = mdecrypt_generic($td, $code);

	  mcrypt_generic_deinit($td);
	  mcrypt_module_close($td);

	  return utf8_encode(trim($decrypted));
	}

	protected function Hex2bin($hexdata) {
	  $bindata = '';

	  for ($i = 0; $i < strlen($hexdata); $i += 2) {
			$bindata .= chr(hexdec(substr($hexdata, $i, 2)));
	  }

	  return $bindata;
	}

}

 
?>
