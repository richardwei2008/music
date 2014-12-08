<?php ob_start(); ?>
<!DOCTYPE html>
<html>
  <?php
  require_once(dirname(__FILE__) . "/server/php/config/AppConfig.php");
  require_once(dirname(__FILE__) . "/server/php/common/CommonLib.php");
  use com\beyond\mumm\music\config\AppConfig;
  use com\beyond\common\CommonLib;

  session_start();

  $queryString = $_SERVER['QUERY_STRING'];
  $index = strpos($queryString, "openid");
  if ($index > 0) {
      $queryString = substr($queryString, 0, $index-1);
  }
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
          $openid = $sessionUser['openid'];
          if (!isset($_SESSION['openid'])) {
              $_SESSION['openid'] = $openid;
          }
          CommonLib::my_debug($_SERVER['PHP_SELF']." Session Openid ".$_SESSION['openid']);
          CommonLib::my_debug($_SERVER['PHP_SELF']." Session User ".json_encode($sessionUser));

          if (empty($queryString) || strpos($queryString, "openid") < 0) {
              CommonLib::my_debug("Preview redirect");
              header("Location: ".$_SESSION['target'].'&'.'openid='.$openid);
              ob_end_flush();
              exit();
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
      <meta name=”description” content="独乐何不众乐？欢庆岂能一人独享！">
      <title>G.H.MUMM-Celebrating F1rst</title>
      <!-- Path to Framework7 Library CSS-->
      <link rel="stylesheet" href="css/jquery.mobile-1.4.4.min.css">

      <!-- Bootstrap styles -->
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <!-- Path to your custom app styles-->
      <link rel="stylesheet" href="css/my-app.css">
      <link rel="stylesheet" href="css/my-app.more.css">
      <link rel="stylesheet" href="css/loader.css">
      <!--<link rel="stylesheet" href="less/my-app-face.css">-->


      <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
      <script type="text/javascript" src="js/jquery.mobile-1.4.4.min.js"></script>
      <script type="text/javascript" src="js/app.wechat.share-0.1.1.js"></script>
      <script type="text/javascript" src="js/beyond.jquery.extension-1.0.js"></script>

      <script type="text/javascript" src="js/app-1.0.0.js"></script>

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

  <div data-role="page" id="view-more" data-theme="d" class="view-more-page">
      <div data-role="content" class="ui-content-custom ">
          <div class="more-page-fill"></div>
          <div class="more-page-content" align="center">
              <div class="more-preview preview-scrollbar" align="center">
                  <ul>
                      <li class="more-item">
                          <div class="more-support-icon more-support-icon-size">
                              <input type="hidden" value="">
                          </div>
                      </li>
                      <li class="more-item">
                          <div class="more-support-icon more-support-icon-size">
                              <input type="hidden" value="">
                          </div>
                      </li>
                      <li class="more-item">
                          <div class="more-support-icon more-support-icon-size">
                              <input type="hidden" value="">
                          </div>
                      </li>
                      <li class="more-item">
                          <div class="more-support-icon more-support-icon-size">
                              <input type="hidden" value="">
                          </div>
                      </li>
                      <li class="more-item">
                          <div class="more-support-icon more-support-icon-size">
                              <input type="hidden" value="">
                          </div>
                      </li>
                      <li class="more-item">
                          <div class="more-support-icon more-support-icon-size">
                              <input type="hidden" value="">
                          </div>
                      </li>
                      <li class="more-item">
                          <div class="more-support-icon more-support-icon-size">
                              <input type="hidden" value="">
                          </div>
                      </li>
                      <li class="more-item">
                          <div class="more-support-icon more-support-icon-size">
                              <input type="hidden" value="">
                          </div>
                      </li>
                      <li class="more-item">
                          <div class="more-support-icon more-support-icon-size">
                              <input type="hidden" value="">
                          </div>
                      </li>
                      <li class="more-item">
                          <div class="more-support-icon more-support-icon-size">
                              <input type="hidden" value="">
                          </div>
                      </li>
                      <li class="more-item">
                          <div class="more-support-icon more-support-icon-size">
                              <input type="hidden" value="">
                          </div>
                      </li>
                      <li class="more-item">
                          <div class="more-support-icon more-support-icon-size">
                              <input type="hidden" value="">
                          </div>
                      </li>
                      <li class="more-item">
                          <div class="more-support-icon more-support-icon-size">
                              <input type="hidden" value="">
                          </div>
                      </li>
                      <li class="more-item">
                          <div class="more-support-icon more-support-icon-size">
                              <input type="hidden" value="">
                          </div>
                      </li>
                      <li class="more-item">
                          <div class="more-support-icon more-support-icon-size">
                              <input type="hidden" value="">
                          </div>
                      </li>
                      <li class="more-item">
                          <div class="more-support-icon more-support-icon-size">
                              <input type="hidden" value="">
                          </div>
                      </li>
                  </ul>
              </div>
          </div>
          <script>
              (function () {
                  $.ajax({
                      async: false,
                      url: 'server/php/service/SupportService.php',
                      type: "POST",
                      data : JSON.stringify({method : 'POST', type : 'MORE'}),
                      dataType : "json",
                      timeout: 10000,
                      success: function (response) {
                          App.debug("response: " + JSON.stringify(response));
                          if (response.success) {
//                                      window.location.href = window.location.href.substring(0, window.location.href.lastIndexOf('/')) + "/preview.php",
//                                          window.event.returnValue = false;
//                                      return false;
                              if (response.data) {
                                  $(response.data).each(function(i) {
                                      if (!$.isEmptyObject(this.url) && !$.isEmptyObject(this.openid)) {
                                          var item = $(".more-item:nth-child(" + i +")");
                                          $(item).children(".more-support-icon").children("input").val(this.openid);
                                          $(item).children(".more-support-icon").css("background-image", "url('" + this.url + "')");
                                      }
                                  })
                              }
                          } else {
                              switch (response.code) {
                                  case 'I001':
                                  case 'E001':
                                  case 'E002':
                                  case 'E003':
                                  case 'E004':
                                  case 'E005':
                                      $("#errorMsg").html(response.message);
                                      break;
                                  default :
                                      $("#errorMsg").html("服务器繁忙，<br>请稍后再来！");
                                      break;
                              }
                              document.getElementById('messageMask').style.display='block';
                          }
                          $.mobile.loading( 'hide');
                      },
                      error : function (xhr, textStatus, errorThrown) {
                          App.debug("response: " + JSON.stringify(xhr));
                          $("#errorMsg").html("服务器繁忙，<br>请稍后再来！");
                          document.getElementById('messageMask').style.display='block';
                      }
                  });

                  $(".more-item").unbind("click").bind("click", function() {
                      return function(that) {
                          var openid = $(that).children(".more-support-icon").children("input").val();
                          var redirectToUrl = window.location.href.substring(0, window.location.href.lastIndexOf('/')) + "/share.php";
                          if (!$.isEmptyObject(openid)) {
                              redirectToUrl = redirectToUrl + "?sid=" + openid;
                              App.debug("Redirect: " + redirectToUrl);
                              window.location.href = redirectToUrl;
                              window.event.returnValue = false;
                          }
                          return false;
                      }(this);
                  })
              })();
          </script>
          <div class="more-page-footer">
              <div id="button-enter" class="ui-button-custom ui-button-back"></div>
          </div>
      </div>
     </div>
  </body>
</html>