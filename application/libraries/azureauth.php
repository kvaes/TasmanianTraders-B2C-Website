<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once __DIR__ . '/vendor/autoload.php';

use \Firebase\JWT\JWT;



class AzureAuth {

  public function __construct()
	{
		// nothing to see here
	}

  public function getProvider() {
    $clientid = getenv('clientid');
    $clientsecret = getenv('clientsecret'); 
    $redirecturl = getenv('redirecturl');
    
    $provider = new TheNetworg\OAuth2\Client\Provider\Azure([
        'clientId'          => $clientid,
        'clientSecret'      => $clientsecret,
        'redirectUri'       => $redirecturl 
    ]);
    return $provider;
  }

  public function getAuthUrl($provider) {
    $provider->tenant = "kvaesb2ctest.onmicrosoft.com";
    $provider->pathAuthorize = "/oauth2/v2.0/authorize";
    $provider->pathToken = "/oauth2/v2.0/token";
    $provider->scope = ["openid"];
    $authUrl = $provider->getAuthorizationUrl([
        'p' => 'B2C_1_demo-signin-basic'
    ]);
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: '.$authUrl);
    exit;
  }

  public function setCode($provider) {
    $provider->tenant = "kvaesb2ctest.onmicrosoft.com";
    $provider->pathAuthorize = "/oauth2/v2.0/authorize";
    $provider->pathToken = "/oauth2/v2.0/token";
    print_r($_GET);
    $token = $provider->getAccessToken('authorization_code', [
      'code' => $_GET['code']
    ]);
    print_r($token);
    $_SESSION['auth_accesstoken'] = $token->getToken();
    $_SESSION['auth_authorizationcode'] = $_GET['code'];
    print_r($_SESSION);
    header('Location: '.base_url());
    exit;
  }

  public function logout($provider) {
    $_SESSION['auth_accesstoken'] = "";
    $_SESSION['auth_authorizationcode'] = "";
    $post_logout_redirect_uri = base_url();
    $logoutUrl = $provider->getLogoutUrl($post_logout_redirect_uri);
    header('Location: '.$logoutUrl);
  }

  public function logged_in($provider) {
    $return = false;
    if (isset($_SESSION['auth_accesstoken'])) {
      $accesstoken = $_SESSION['auth_accesstoken'];
      if ($accesstoken <> "") {
        $clientid = getenv('clientid');
        $token = $this->validateAccessToken($accesstoken,$clientid);
        $return = true;
      }
    }
    return $return;
  }

  public function getProfile($provider) {
    $return = "";
    if (isset($_SESSION['auth_accesstoken'])) {
      $accesstoken = $_SESSION['auth_accesstoken'];
      if ($accesstoken <> "") {
        $clientid = getenv('clientid');
        $return = $this->validateAccessToken($accesstoken,$clientid);
      }
    }
    if ($return == "") { $this->getAuthUrl($provider); }
    return $return;
  }

  public function loadKeysFromAzure($string_microsoftPublicKeyURL) {
      $array_keys = array();
      $jsonString_microsoftPublicKeys = file_get_contents($string_microsoftPublicKeyURL);
      $array_microsoftPublicKeys = json_decode($jsonString_microsoftPublicKeys, true);
      foreach($array_microsoftPublicKeys['keys'] as $array_publicKey) {
          $string_certText = "-----BEGIN CERTIFICATE-----\r\n".chunk_split($array_publicKey['x5c'][0],64)."-----END CERTIFICATE-----\r\n";
          $array_keys[$array_publicKey['kid']] = $this->getPublicKeyFromX5C($string_certText);
      }
      return $array_keys;
  }

  public function getPublicKeyFromX5C($string_certText) {
      $object_cert = openssl_x509_read($string_certText);
      $object_pubkey = openssl_pkey_get_public($object_cert);
      $array_publicKey = openssl_pkey_get_details($object_pubkey);
      return $array_publicKey['key'];
  }

  public function validateAccessToken($accessToken,$clientid) {
    if ($accessToken == "") {
      echo "No access token retrieved";
    } else {
      $string_microsoftPublicKeyURL = 'https://login.microsoftonline.com/kvaesb2ctest.onmicrosoft.com/discovery/v2.0/keys?p=b2c_1_demo-signin-basic';
      $array_publicKeysWithKIDasArrayKey = $this->loadKeysFromAzure($string_microsoftPublicKeyURL);	
          $token = JWT::decode($accessToken, $array_publicKeysWithKIDasArrayKey, array('RS256'));
          return $token;
    }
  }


}

?>  
