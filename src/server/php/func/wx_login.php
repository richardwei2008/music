<?php ob_start();

require_once(dirname(__FILE__) ."/../config/AppConfig.php");
require_once(dirname(__FILE__) . "/../common/CommonLib.php");
use com\beyond\mumm\music\config\AppConfig;
use com\beyond\common\CommonLib;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-10-28
 * Time: 下午12:56
 */

session_start();
//$nowid=session_id();
//CommonLib::my_debug("Session Id before set in wx_login ".$nowid);
//$sid=$_GET['sid'];
//if($sid != $nowid)
//      session_id($sid);
//CommonLib::my_debug("Session Id after set in wx_login ".session_id());
if(isset($_SESSION['views']))
    $_SESSION['views']=$_SESSION['views']+1;
else
    $_SESSION['views']=1;


$target = AppConfig::AUTH_URL;
$_SESSION['home'] = AppConfig::HOME_URL;
CommonLib::my_debug("Session Views ".$_SESSION['views']);
CommonLib::my_debug("Session Target After ob_start in wx_login ".$_SESSION['target']);

$user_obj = $_SESSION['user'];
CommonLib::my_debug("wx_login Session User ".json_encode($user_obj));
if (isset($user_obj) && !empty($user_obj['openid'])) {
    CommonLib::my_debug("User ".$user_obj);
    $openid = $user_obj['openid'];
    CommonLib::my_debug("Openid ".$openid);
    if(!empty($_SESSION['target'])) {
        $target = $_SESSION['target'];
    } else {
        $target = $_SESSION['home'].'?&'.'openid='.$openid;
    }
    CommonLib::my_debug("Target A ".$target);
} else {
    $redirecturl = CommonLib::encodeURIComponent($target);
    $appid = "wxc63c757bdae5dd41";
    $base_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$redirecturl.'&response_type=code&scope=snsapi_base&state=1#wechat_redirect';
    $info_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$redirecturl.'&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect';
    $target = $base_url;
    CommonLib::my_debug("Target B ".$target);
}

header("Location:".$target);
ob_end_flush();
exit();
?>