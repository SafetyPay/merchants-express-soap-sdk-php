<?php
error_reporting(E_ALL & ~(E_WARNING|E_NOTICE));
require_once '../class/SafetyPayProxy.php';

$proxy = new SafetyPayProxy();

$tokenURL = $proxy->GetNewTokenID();

if (!defined('JSON_UNESCAPED_SLASHES')) {
    define('JSON_UNESCAPED_SLASHES', 64);
}
header('Content-Type: application/json');
echo str_replace('\\/', '/', json_encode(array("tokenURL" => $tokenURL), JSON_UNESCAPED_SLASHES));
