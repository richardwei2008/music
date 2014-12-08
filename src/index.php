<?php ob_start(); ?>
<!DOCTYPE html>
<html>
<?php
require_once(dirname(__FILE__) . "/server/php/config/AppConfig.php");
require_once(dirname(__FILE__) . "/server/php/common/CommonLib.php");
require_once(dirname(__FILE__) . "/server/php/service/UserService.php");

use com\beyond\mumm\music\config\AppConfig;
use com\beyond\common\CommonLib;
use com\beyond\mumm\music\user\service\UserService;


session_start();
CommonLib::my_debug("Session Id in index ".session_id());

if(isset($_SESSION['views']))
    $_SESSION['views']=$_SESSION['views']+1;
else
    $_SESSION['views']=1;

$queryString = $_SERVER['QUERY_STRING'];
$index = strpos($queryString, "openid");
if ($index > 0) {
    $queryString = substr($queryString, 0, $index-1);
}

CommonLib::my_debug("QueryString ".$queryString);
$_SESSION['target'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$queryString;
$_SESSION['home'] = AppConfig::HOME_URL;

$sessionUser = $_SESSION['user'];
CommonLib::my_debug($_SERVER['PHP_SELF']." Session User ".json_encode($sessionUser));
$openid = $_SESSION['openid'];
//if ($openid == 'oxqECj13-L8dz--q6dd9Z34ouTfc') {
//    $sessionUser = array("errcode"=>48001,"errmsg"=>"api unauthorized");
//    $_SESSION['user'] = $sessionUser;
//    $openid = $_SESSION['openid'];
//}
if (empty($sessionUser) || empty($sessionUser['openid'])) {
    CommonLib::my_debug("Empty #################### SESSION with VIEW:".$_SESSION['views']);
    CommonLib::my_debug("Session Target Before flush ".$_SESSION['target']);
    header("Location:".AppConfig::LOGIN_URL);
    ob_end_flush();
    CommonLib::my_debug("Session Target After flush ".$_SESSION['target']);
    exit();
} else {
    CommonLib::my_debug("Exist #################### SESSION with VIEW:".$_SESSION['views']);
    $openid = $sessionUser['openid'];
    if (!isset($_SESSION['openid'])) {
        $_SESSION['openid'] = $openid;
    }
    CommonLib::my_debug($_SERVER['PHP_SELF']." Session Openid ".$_SESSION['openid']);
    CommonLib::my_debug($_SERVER['PHP_SELF']." Session User ".json_encode($sessionUser));
//    echo("Session User");
//    print_r($_SESSION['user']);

    $userService = new UserService();
    $ret = $userService->updateUser($openid, $sessionUser);
    if (!$ret) {
        CommonLib::my_debug($_SERVER['PHP_SELF'].' Update Failed');
        header("Location:".AppConfig::LOGIN_URL);
        ob_end_flush();
        exit();
    } else {
        // navigation to mine and load support with flash
        CommonLib::my_debug("Redirect ################### TARGET:".$redirect);
        if (empty($_SERVER['QUERY_STRING']) || strpos($_SERVER['QUERY_STRING'], "openid") < 0) {
            $redirect = $_SESSION['target'].'&'.'openid='.$openid;
            CommonLib::my_debug("Redirect ################### TARGET:".$redirect);
            header("Location: ".$redirect);
            ob_end_flush();
            exit();
        }
        CommonLib::my_debug("Enter ################### TARGET:".$_SESSION['target']);
    }
}
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui, target-densitydpi=high-dpi">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
      <meta name="format-detection" content="telephone=no">
    <meta name="description" content="独乐何不众乐？欢庆岂能一人独享！">
      <title>G.H.MUMM-Celebrating F1rst</title>


      <!-- Path to Framework7 Library CSS-->
      <link rel="stylesheet" href="css/jquery.mobile-1.4.4.min.css">
      <!-- Bootstrap styles -->
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <!-- Path to your custom app styles-->
      <link rel="stylesheet" href="css/my-app.css">
      <link rel="stylesheet" href="css/loader.css">


      <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
      <script type="text/javascript" src="js/jquery.mobile-1.4.4.min.js"></script>

      <script type="text/javascript" src="js/beyond.jquery.extension-1.0.js"></script>

      <script type="text/javascript" src="js/app.wechat.share-0.1.1.js"></script>
      <script type="text/javascript" src="js/app-1.0.0.js"></script>
  </head>
  <body class="ui-mobile-viewport ui-overlay-a">

    <div class="loader"></div>

    <div id="messageMask" onClick="document.getElementById('messageMask').style.display='none';"  class="message-mask">
        <div id="errorBox" class="message-content" align="center">
            <div style="float:right; width:20px; height:20px;cursor:hand;background-image:url('images/close.png');background-repeat: no-repeat;"></div>
            <div id="errorMsg" class="message-font" align="center">
                错误提示！
            </div>
        </div>
    </div>

    <div data-role="page" id="view-home" data-theme="d" class="home-page">
        <div data-role="content" class="ui-content-custom ">
            <div class="home-page-fill"></div>
            <div class="home-page-content" align="center">
                <div id="button-enter-shadow" class="ui-button-play-shadow"></div>
                <div id="button-enter" class="ui-button-play"></div>
            </div>
        </div>
    </div>
    <script>
        (function() {
            var imgload = function () {
                var shadow = $("#button-enter-shadow");
                var timer = setInterval(function() {
                    if ($(shadow).css("opacity") > 0.95) {
                        $('#button-enter-shadow').animate({opacity: 0.5}, 1000);
                    } else if ($(shadow).css("opacity") < 0.55) {
                        $('#button-enter-shadow').animate({opacity: 1}, 1000);
                    }
                }, 1000);
            };
            imgload();
        })();
    </script>
  </body>
</html>