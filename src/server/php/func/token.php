<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-11-4
 * Time: 上午11:03
 */
require_once(dirname(__FILE__) ."/../config/AppConfig.php");
require_once(dirname(__FILE__) . "/../common/CommonLib.php");
use com\beyond\mumm\music\config\AppConfig;
use com\beyond\common\CommonLib;

$mmc = memcache_init(); //初始化缓存
$token = memcache_get($mmc, "token"); //获取Token
if (empty($token)) {
    CommonLib::my_debug("Empty token from memcache ".$token);
    $appid = AppConfig::APP_ID;
    $secret = AppConfig::APP_SECRET;
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $a = curl_exec($ch);
    $strjson = json_decode($a);
    CommonLib::my_debug("Response from client-credential ".$a);
    $access_token = $strjson->access_token;
    CommonLib::my_debug("Set token to memcache ".$access_token);
    memcache_set($mmc, "token", $access_token, 0, 7200); //过期时间为7200秒
    $token = memcache_get($mmc, "token"); //获取Token
}
CommonLib::my_debug("Token from memcache ".$token);
?>