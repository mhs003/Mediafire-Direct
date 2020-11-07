<?php
/**
 * Mediafire Direct
 * @auth: Monzurul Hasan 
 * @file: logic.php
 * @class: logic
 * @date: 7/11/2020
 */
 
class logic{
  public $url = null;
  private $use_proxy = true; // change to `false` if having any trouble with proxy server
  public $proxy = "69.28.62.166:3128"; // important
  
  public function __construct(){
    
  }
  
  public function mediafire($url){
    if($this->hasStr(strtolower($url), "mediafire.com") && filter_var($url, FILTER_VALIDATE_URL)){
      $this->url = $url;
      return true;
    } else {
      return false;
    }
  }
  
  public function getDirectLink(){
    $url = $this->url;
    $proxy = $this->proxy;
    
    $data = array();
    
    $resp = $this->getWebDatas();
    $errno = $resp['errno'];
    $errmsg = $resp['errmsg'];
    $html = $resp['content'];
    
    if($errno != 0){
      $data = [
        'error' => true,
        'reason' => $errmsg
      ];
    } else {
      $rhtml = preg_replace('/\n/', '', $html);
      $rlink = preg_replace('/(.*)<a class="input popsok"                        aria-label="Download file"                        href="(.*)">                        Download(.*)/', '$2', $rhtml);
      $data = [
        'error' => false,
        'file_link' => $rlink
      ];
    }
    
    return $data;
  }
  
  private function getWebDatas(){
    $url = $this->url;
    $proxy = $this->proxy;
    
    $cu_opts = [
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_PROXY => ($this->use_proxy == true) ? $proxy : false,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HEADER => false,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_AUTOREFERER => true,
      CURLOPT_CONNECTTIMEOUT => 120,
      CURLOPT_TIMEOUT => 120,
      CURLOPT_MAXREDIRS => 10
    ];
    
    $ch = curl_init($url);
    curl_setopt_array($ch, $cu_opts);
    $content = curl_exec($ch);
    $err = curl_errno($ch);
    $errmsg = curl_error($ch);
    $header = curl_getinfo($ch);
    curl_close($ch);

    $header['errno'] = $err;
    $header['errmsg'] = $errmsg;
    $header['content'] = $content;
    return $header;
  }
  
  private function hasStr($str, $find){
    if(strpos($str, $find) !== false){
      return true;
    } else {
      return false;
    }
  }
}
