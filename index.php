<?php
/**
 * Mediafire Direct
 * @auth: Monzurul Hasan 
 * @file: index.php
 * @date: 7/11/2020
 */

error_reporting(0);
header('Content-Type: text/plain');

require_once('logic.php');

$logic = new logic();
$mf = $logic->mediafire('http://www.mediafire.com/file/gi0bvbgforry3qk/file');

if($mf){
  $resp = $logic->getDirectLink();
  print_r($resp);
} else {
  echo "Invalid url";
}
