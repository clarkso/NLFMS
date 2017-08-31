<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class OAuth2
{
    private $dsn      = '';
    private $username = '';
    private $password = '';
    
    public function __construct(){
        // error reporting (this is a demo, after all!)
        ini_set('display_errors',1);error_reporting(E_ALL);
    }
  
    public function initialize_oauth(){
        // Autoloading (composer is preferred, but for this example let's just do this)  
        require_once('src/OAuth2/Autoloader.php');  
        OAuth2\Autoloader::register();  
          
        // $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"  
        $storage = new OAuth2\Storage\Pdo(array('dsn' => $this->dsn, 'username' => $this->username, 'password' => $this->password));  
          
        // Pass a storage object or array of storage objects to the OAuth2 server class  
        //$server = new OAuth2\Server($storage);  
        $server = new OAuth2\Server($storage, array(  
            'allow_implicit' => true,  
            'refresh_token_lifetime'=> 2419200,  
        ));  
        // Add the "Client Credentials" grant type (it is the simplest of the grant types)  
        $server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));  
          
        // Add the "Authorization Code" grant type (this is where the oauth magic happens)  
        $server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));  
        //Resource Owner Password Credentials (资源所有者密码凭证许可）  
        $server->addGrantType(new OAuth2\GrantType\UserCredentials($storage));  
        //can RefreshToken set always_issue_new_refresh_token=true  
        $server->addGrantType(new OAuth2\GrantType\RefreshToken($storage, array(  
            'always_issue_new_refresh_token' => true  
        )));  
        // configure your available scopes  
        $defaultScope = $storage->getDefaultScope();  
        $supportedScopes = array(  
            'lj_user',  
            'lj_login',
            'lj_card',
            'lj_pay'
        );  
        $memory = new OAuth2\Storage\Memory(array(  
            'default_scope' => $defaultScope,  
            'supported_scopes' => $supportedScopes  
        ));  
        $scopeUtil = new OAuth2\Scope($memory);  
        $server->setScopeUtil($scopeUtil);  
        
        return $server;
    }
    
    public function control_storage(){
        $storage = new OAuth2\Storage\Pdo(array('dsn' => $this->dsn, 'username' => $this->username, 'password' => $this->password));
        return $storage;
    }

}

 
?>
