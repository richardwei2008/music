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
$openid = $_GET["openid"];
$test  = $_GET["test"];
if (!empty($test)) {
    $_SESSION['openid'] = $openid;
    $_SESSION['user'] = array("openid"=>openid, "nickname"=>"FakeUser");
} else {
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
}

?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="description" content="独乐何不众乐？欢庆岂能一人独享！">
    <title>G.H.MUMM-Celebrating F1rst</title>


    <!-- Path to Framework7 Library CSS-->
    <link rel="stylesheet" href="css/jquery.mobile-1.4.4.min.css">

    <!-- Bootstrap styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Generic page styles -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Path to your custom app styles-->
    <link rel="stylesheet" href="css/my-app.css">
     <link rel="stylesheet" href="css/my-app.upload.css">
    <link rel="stylesheet" href="css/loader.css">
    <!--<link rel="stylesheet" href="less/my-app-face.css">-->
    <style>
        /* Let's get this party started */
        ::-webkit-scrollbar {
            width: 6px;
        }
        /* Track */
        ::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
            -webkit-border-radius: 10px;
            border-radius: 10px;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            -webkit-border-radius: 10px;
            border-radius: 10px;
            background: rgba(195,0,47, 1);
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5);
        }
        ::-webkit-scrollbar-thumb:window-inactive {
            background: rgba(195,0,47, 1);
        }

    </style>
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/beyond.jquery.extension-1.0.js"></script>


</head>
<body class="ui-mobile-viewport ui-overlay-a">
<!-- Status bar overlay for fullscreen mode-->
<!--<div class="statusbar-overlay"></div>-->
<!-- Panels overlay-->
<!--<div class="panel-overlay"></div>-->
<!-- Views, and they are tabs-->
<!-- We need to set "toolbar-through" class on it to keep space for our tab bar-->
  <div class="loader"></div>

<div id="messageMask" onClick="document.getElementById('messageMask').style.display='none';"  class="message-mask">
    <div id="errorBox" class="message-content" align="center">
        <div style="float:right; width:20px; height:20px;cursor:hand;background-image:url('images/close.png');background-repeat: no-repeat;"></div>
        <div id="errorMsg" class="message-font" align="center">
            错误提示！
        </div>
    </div>
</div>


<div data-role="page" id="view-music" data-theme="d" class="view-music-page">
    <div data-role="content" class="ui-content-custom">
        <div class="music-page-fill"></div>
        <div class="music-page-content">
            <div class="music-preview-section">
                <div class="music-preview preview-scrollbar">
                    <ul>
                        <li class="music-item" style="display: none">1. MUMM DG Music-part1
                            <div class="audio-control">
                                <!--autoplay="autoplay"-->
                                <audio src="http://com-beyond2005-test.qiniudn.com/mumm_music_MUMM_DG_Music-part1.mp3" type="audio/mpeg">
                                    您的浏览器不支持 audio 标签。
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_MUMM_DG_Music-part1.ogg" type="audio/ogg">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_MUMM_DG_Music-part1.wav" type="audio/wav">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_MUMM_DG_Music-part1.mp3" type="audio/mpeg">
                                </audio>
                            </div>
                        </li>
                        <li class="music-item" style="display: none">2. MUMM DG Music-part2
                            <div class="audio-control">
                                <audio src="http://com-beyond2005-test.qiniudn.com/mumm_music_MUMM_DG_Music-part2.mp3" type="audio/mpeg">
                                    您的浏览器不支持 audio 标签。
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_MUMM_DG_Music-part2.ogg" type="audio/ogg">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_MUMM_DG_Music-part2.wav" type="audio/wav">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_MUMM_DG_Music-part2.mp3" type="audio/mpeg">
                                </audio>
                            </div>
                        </li>
                        <li class="music-item" style="display: none">3. MUMM DG Music-part3
                            <div class="audio-control">
                                <audio src="http://com-beyond2005-test.qiniudn.com/mumm_music_MUMM_DG_Music-part3.mp3" type="audio/mpeg">
                                    您的浏览器不支持 audio 标签。
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_MUMM_DG_Music-part3.ogg" type="audio/ogg">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_MUMM_DG_Music-part3.wav" type="audio/wav">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_MUMM_DG_Music-part3.mp3" type="audio/mpeg">
                                </audio>
                            </div>
                        </li >
                        <li class="music-item" style="display: none">4. MUMM DG Music-part4
                            <div class="audio-control">
                                <audio src="http://com-beyond2005-test.qiniudn.com/mumm_music_MUMM_DG_Music-part4.mp3" type="audio/mpeg">
                                    您的浏览器不支持 audio 标签。
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_MUMM_DG_Music-part4.ogg" type="audio/ogg">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_MUMM_DG_Music-part4.wav" type="audio/wav">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_MUMM_DG_Music-part4.mp3" type="audio/mpeg">
                                </audio>
                            </div>
                        </li>
                        <li class="music-item music-item-active">01. David Guetta-Dangerous-Part1
                            <div class="audio-control">
                                <!--autoplay="autoplay"-->
                                <audio src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part1.m4a" type="audio/mpeg">
                                    您的浏览器不支持 audio 标签。
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part1.ogg" type="audio/ogg">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part1.wav" type="audio/wav">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part1.m4a" type="audio/mpeg">
                                </audio>
                            </div>
                        </li>
                        <li class="music-item">02. David Guetta-Dangerous-Part2
                            <div class="audio-control">
                                <audio src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part2.m4a" type="audio/mpeg">
                                    您的浏览器不支持 audio 标签。
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part2.ogg" type="audio/ogg">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part2.wav" type="audio/wav">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part2.m4a" type="audio/mpeg">
                                </audio>
                            </div>
                        </li>
                        <li class="music-item">03. David Guetta-Dangerous-Part3
                            <div class="audio-control">
                                <audio src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part3.m4a" type="audio/mpeg">
                                    您的浏览器不支持 audio 标签。
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part3.ogg" type="audio/ogg">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part3.wav" type="audio/wav">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part3.m4a" type="audio/mpeg">
                                </audio>
                            </div>
                        </li >
                        <li class="music-item">04. David Guetta-Dangerous-Part4
                            <div class="audio-control">
                                <audio src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part4.m4a" type="audio/mpeg">
                                    您的浏览器不支持 audio 标签。
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part4.ogg" type="audio/ogg">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part4.wav" type="audio/wav">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part4.m4a" type="audio/mpeg">
                                </audio>
                            </div>
                        </li>
                        <li class="music-item">05. David Guetta-Dangerous-Part5
                            <div class="audio-control">
                                <audio src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part5.m4a" type="audio/mpeg">
                                    您的浏览器不支持 audio 标签。
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part5.ogg" type="audio/ogg">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part5.wav" type="audio/wav">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part5.m4a" type="audio/mpeg">
                                </audio>
                            </div>
                        </li>
                        <li class="music-item">06. David Guetta-Dangerous-Part6
                            <div class="audio-control">
                                <audio src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part6.m4a" type="audio/mpeg">
                                    您的浏览器不支持 audio 标签。
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part6.ogg" type="audio/ogg">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part6.wav" type="audio/wav">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part6.m4a" type="audio/mpeg">
                                </audio>
                            </div>
                        </li>
                        <li class="music-item">07. David Guetta-Dangerous-Part7
                            <div class="audio-control">
                                <audio src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part7.m4a" type="audio/mpeg">
                                    您的浏览器不支持 audio 标签。
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part7.ogg" type="audio/ogg">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part7.wav" type="audio/wav">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part7.m4a" type="audio/mpeg">
                                </audio>
                            </div>
                        </li >
                        <li class="music-item">08. David Guetta-Dangerous-Part8
                            <div class="audio-control">
                                <audio src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part8.m4a" type="audio/mpeg">
                                    您的浏览器不支持 audio 标签。
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part8.ogg" type="audio/ogg">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part8.wav" type="audio/wav">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part8.m4a" type="audio/mpeg">
                                </audio>
                            </div>
                        </li>
                        <li class="music-item">09. David Guetta-Dangerous-Part9
                            <div class="audio-control">
                                <audio src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part9.m4a" type="audio/mpeg">
                                    您的浏览器不支持 audio 标签。
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part9.ogg" type="audio/ogg">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part9.wav" type="audio/wav">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part9.m4a" type="audio/mpeg">
                                </audio>
                            </div>
                        </li>
                        <li class="music-item">10. David Guetta-Dangerous-Part10
                            <div class="audio-control">
                                <audio src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part10.m4a" type="audio/mpeg">
                                    您的浏览器不支持 audio 标签。
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part10.ogg" type="audio/ogg">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part10.wav" type="audio/wav">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part10.m4a" type="audio/mpeg">
                                </audio>
                            </div>
                        </li>
                        <li class="music-item">11. David Guetta-Dangerous-Part11
                            <div class="audio-control">
                                <audio src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part11.m4a" type="audio/mpeg">
                                    您的浏览器不支持 audio 标签。
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part11.ogg" type="audio/ogg">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part11.wav" type="audio/wav">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part11.m4a" type="audio/mpeg">
                                </audio>
                            </div>
                        </li >
                        <li class="music-item">12. David Guetta-Dangerous-Part12
                            <div class="audio-control">
                                <audio src="http://com-beyond2005-test.qiniudn.com/mumm_music_David-Guetta-Dangerous-Part12.m4a" type="audio/mpeg">
                                    您的浏览器不支持 audio 标签。
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part12.ogg" type="audio/ogg">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part12.wav" type="audio/wav">
                                    <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part12.m4a" type="audio/mpeg">
                                </audio>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
<!--            <a id="button-submit" href="javascript:void(0)" class="button-submit-page ui-link"></a>-->
        </div>
        <div class="music-page-footer">
            <a href="javascript:void(0)">
                <div id="button-submit" class="button-submit-page"></div>
            </a>
        </div>
    </div>
</div>

    <div data-role="page" id="view-rule" data-theme="d" class="view-rule-page">
        <div data-role="content" class="ui-content-custom ">
            <div class="rule-page-fill"></div>
            <div class="rule-page-content">
                <a href="#view-enter"><div class="ui-button-custom ui-button-back">

                </div></a>
            </div>
        </div>
    </div>


<div data-role="page" id="view-submit" data-theme="d" class="view-submit-page">
    <div data-role="content">
        <div class="">
            <div class="submit-page-btn-group">
                <input type="text" name="nickname" id="nickname" class="ui-input-custom">
                <input type="text" name="cellphone" id="cellphone" class="ui-input-custom" minlength="11" maxlength="11" required>
            </div>
            <a id="button-preview-mock" href="preview.html" class="button-preview-page ui-link" data-ajax="false"></a>
        </div>
    </div>
</div>

<script type="text/javascript" src="js/jquery.mobile-1.4.4.min.js"></script>
<script type="text/javascript" src="js/app.wechat.share-0.1.1.js"></script>
<script type="text/javascript" src="js/app-1.0.0.js"></script>
</body>
</html>