<?php  ob_start();
require_once(dirname(__FILE__) ."/../config/AppConfig.php");
require_once(dirname(__FILE__) . "/../common/CommonLib.php");

use com\beyond\mumm\music\config\AppConfig;
use com\beyond\common\CommonLib;

session_start();
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-10-28
 * Time: 下午1:00
 */
//
//$appid = "wxc63c757bdae5dd41";
//$secret = "05fc96a2887de802e694252b040fc53f";
$appid = AppConfig::APP_ID;
$secret = AppConfig::APP_SECRET;
$code = $_GET["code"];
CommonLib::my_debug("auth_callback ".$code);
$get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';

$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $get_token_url);
curl_setopt($ch,CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
$res = curl_exec($ch);
curl_close($ch);

CommonLib::my_debug("auth_callback access_toke response ".$res);
$json_obj = json_decode($res,true);

//根据openid和access_token查询用户信息
$token = $json_obj['access_token'];
$openid = $json_obj['openid'];


CommonLib::my_debug("auth_callback access_toke ".$token);
CommonLib::my_debug("auth_callback openid ".$openid);
$get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$token.'&openid='.$openid.'&lang=zh_CN';

$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$get_user_info_url);
curl_setopt($ch,CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
$res = curl_exec($ch);
curl_close($ch);

CommonLib::my_debug("auth_callback userinfo response ".$res);
//解析json
$user_obj = json_decode($res, true);
if (!empty($user_obj['errcode'])) {
    $_SESSION['user'] = array("openid"=>$openid, "nickname"=>"F1rsts客户");
} else {
    $_SESSION['user'] = $user_obj;
//print_r($user_obj);
    $openid = $user_obj['openid'];
}
CommonLib::my_debug("Openid ".$openid);
if(!empty($_SESSION['target'])) {
    $target = $_SESSION['target'].'&'.'openid='.$openid;
    CommonLib::my_debug("Auth Target A ".$target);
} else {
    $target = $_SESSION['home'].'?&'.'openid='.$openid;
    CommonLib::my_debug("Auth Target B ".$target);
}
header("Location:".$target);
//header("Location:".'http://www.sina.com.cn/');
ob_end_flush();
exit();
?>
