<?php ob_start(); ?>
<!DOCTYPE html>
<html>
  <?php
  require_once(dirname(__FILE__) . "/server/php/config/AppConfig.php");
  require_once(dirname(__FILE__) . "/server/php/common/CommonLib.php");
  require_once(dirname(__FILE__) . "/server/php/dao/ContactPDO.php");
  use com\beyond\mumm\music\config\AppConfig;
  use com\beyond\common\CommonLib;

  use com\beyond\mumm\music\profile\dao\ContactPDO;

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

  $category = rand(1,3);
  $engine = $category + 1;
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

          $contactDao = new ContactPDO();
          $find = $contactDao->findByOpenId($openid);
          if (isset($find->category)) {
              $category = $find->category;
              $engine = $category + 1;
          }

          if (empty($queryString) || strpos($queryString, "openid") < 0) {
              CommonLib::my_debug("Preview redirect");
              header("Location: ".$_SESSION['target'].'&'.'openid='.$openid);
              ob_end_flush();
              exit();
          }
      }
  }

//  $template = array(
//      "http://beyondwechattest-beyondwechattest.stor.sinaapp.com/oxqECj13-L8dz--q6dd9Z34ouTfc/mumm_music_1AA6077F-2E99-68FC-6E37-50EFC46C520D.jpg",
//      "http://beyondwechattest-beyondwechattest.stor.sinaapp.com/oxqECj13-L8dz--q6dd9Z34ouTfc/mumm_music_932C06B7-C9AD-C852-4A4E-0BECBD576A4C.jpg",
//      "http://beyondwechattest-beyondwechattest.stor.sinaapp.com/oxqECj13-L8dz--q6dd9Z34ouTfc/mumm_music_A1E42A2F-88DC-0EF1-9F11-43F5D262E206.jpg",
//      "http://beyondwechattest-beyondwechattest.stor.sinaapp.com/oxqECj13-L8dz--q6dd9Z34ouTfc/mumm_music_1AA6077F-2E99-68FC-6E37-50EFC46C520D.jpg",
//      "http://beyondwechattest-beyondwechattest.stor.sinaapp.com/oxqECj13-L8dz--q6dd9Z34ouTfc/mumm_music_932C06B7-C9AD-C852-4A4E-0BECBD576A4C.jpg"
//  );
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
      <!-- Path to your custom app styles-->
      <link rel="stylesheet" href="css/my-app.css">
      <link rel="stylesheet" href="css/my-app.mine.css">
      <link rel="stylesheet" href="css/my-app.wowpreview.css">
      <link rel="stylesheet" href="css/loader.css">

<!--      <link rel="stylesheet" type="text/css" href="plugins/slider/engine2/style.css" />-->
      <link rel="stylesheet" href="<?="plugins/slider/engine".$engine."/style.css"?>">

      <!-- End WOWSlider.com HEAD section -->
      <!-- Start Slider Centering-->
      <style>
          .ws-wrapper-centered {
              position: relative;
              background-color: #000000;
              width: 100%;
              height: 100%;
              max-height: 1280px;
              margin: 0 auto;
              overflow: hidden;
          }
          .ws-wrapper-centered > div {
              display: table;
              -webkit-transform: translateY(-0%) translateX(-0%);
              transform: translateY(-0%) translateX(-0%);
              left: 0%;
              top: 0%;
              opacity: 1;
          }


      </style>

      <script type="text/javascript" src="js/app.wechat.share-0.1.1.js"></script>
      <script type="text/javascript" src="js/jquery.wowslider.modify.js"></script>
      <script type="text/javascript" src="js/beyond.jquery.extension-1.0.js"></script>
      <script type="text/javascript" src="js/jquery.mobile-1.4.4.min.js"></script>
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

  <div data-role="page" id="view-mine" data-theme="d" class="view-mine-page">
      <div data-role="content" class="ui-content-custom ">
          <div class="mine-page-fill"></div>
          <div class="mine-page-content">
              <div class="audio-preview-section">
                  <audio loop="loop" src="http://com-beyond2005-test.qiniudn.com/mumm_music_MUMM_DG_Music-part4.mp3" type="audio/mpeg">
                      您的浏览器不支持 audio 标签。
                      <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_MUMM_DG_Music-part4.ogg" type="audio/ogg">
                      <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_MUMM_DG_Music-part4.wav" type="audio/wav">
                      <source src="http://com-beyond2005-test.qiniudn.com/mumm_music_MUMM_DG_Music-part4.mp3" type="audio/mpeg">
                  </audio>
              </div>
              <div class="mv-preview-section mv-preview-section-bg">

                <div class="ws-wrapper-centered">
                    <div class="ws-play-cover ">
                        <div class="ws-play-face"></div>
                        <div id="ws-play-first" class="ws-play-first wowslider-line-height-custom">
                            <img class="ws-cover-images" src="images/samples/C.jpg" width="100%"/>
                        </div>
                    </div>
                    <div id="ws-play-watermark" class="ws-play-watermark" style="background: url(<?="images/album-watermark".$category.".png"?>) no-repeat center;background-size: 100% 100%;"></div>
                  <!-- Start WOWSlider.com BODY section --> <!-- add to the <body> of your page -->
                  <div id="wowslider-container1">
                      <div class="ws_images">
                          <ul>
                              <li class="wowslider-line-height-custom"><img class="flash-images" src="images/samples/1.jpg" alt="" title="" id="wows1_0"/></li>
                              <li class="wowslider-line-height-custom"><img class="flash-images" src="images/samples/2.jpg" alt="" title="" id="wows1_1"/></li>
                              <li class="wowslider-line-height-custom"><img class="flash-images" src="images/samples/3.jpg" alt="" title="" id="wows1_2"/></li>
                              <li class="wowslider-line-height-custom"><img class="flash-images" src="images/samples/4.jpg" alt="" title="" id="wows1_3"/></li>
                              <li class="wowslider-line-height-custom"><img class="flash-images" src="images/samples/5.jpg" alt="" title="" id="wows1_4"/></li>
                          </ul></div>
                      <script>
                          (function () {
    //                          var previewImages = $('.preview-img').filter(function() {return this.src !== ''});
                              var loadPreviewImage = function(previewImages) {
                                  return function() {
                                      var size = previewImages.length;

                                      if (size > 0) {
                                          $('.ws-cover-images')[0].src = previewImages[0];
                                      }

                                      $('.flash-images').each(function(i) {
                                          this.src = previewImages[i % size];
                                      })
                                      $('.flash-tooltips').each(function(i) {
                                          this.src = previewImages[i % size];
                                      })
                                  }(previewImages);
                              };

                              var loadPreviewAudio = function(audioLink) {
                                  return function() {
                                      var audio = $('.audio-preview-section').children('audio')[0];
                                      audio.src = audioLink;
                                      var source = $(audio).children('source');
                                      source[0] = audioLink.replace(".mp3", ".ogg");
                                      source[1] = audioLink.replace(".mp3", ".wav");
                                      source[2] = audioLink.replace(".mp3", ".mp3");

//                                      if (!audio.paused) {
//                                          audio.pause();
//                                          audio.currentTime = 0.0;
//                                      }
//                                      audio.play();
                                  }(audioLink);
                              };
    //                          var template = [
    //                              "http://beyondwechattest-beyondwechattest.stor.sinaapp.com/oxqECj13-L8dz--q6dd9Z34ouTfc/mumm_music_1AA6077F-2E99-68FC-6E37-50EFC46C520D.jpg",
    //                              "http://beyondwechattest-beyondwechattest.stor.sinaapp.com/oxqECj13-L8dz--q6dd9Z34ouTfc/mumm_music_932C06B7-C9AD-C852-4A4E-0BECBD576A4C.jpg",
    //                              "http://beyondwechattest-beyondwechattest.stor.sinaapp.com/oxqECj13-L8dz--q6dd9Z34ouTfc/mumm_music_A1E42A2F-88DC-0EF1-9F11-43F5D262E206.jpg",
    //                              "http://beyondwechattest-beyondwechattest.stor.sinaapp.com/oxqECj13-L8dz--q6dd9Z34ouTfc/mumm_music_1AA6077F-2E99-68FC-6E37-50EFC46C520D.jpg",
    //                              "http://beyondwechattest-beyondwechattest.stor.sinaapp.com/oxqECj13-L8dz--q6dd9Z34ouTfc/mumm_music_932C06B7-C9AD-C852-4A4E-0BECBD576A4C.jpg"
    //                          ];
    //                          $(".flash-images").each(function(i) {
    //                              this.src = template[i];
    //                          });

                              var openid = $.getUrlParam('openid');
                              $.ajax({
                                  async: false,
                                  url: 'server/php/service/MediaService.php',
                                  type: "POST",
                                  data : JSON.stringify({method : 'POST', type : 'READ', openid : openid}),
                                  dataType : "json",
                                  timeout: 10000,
                                  success: function (response) {
    //                                  App.debug("response: " + JSON.stringify(response));
                                      if (response.success) {
    //                                      window.location.href = window.location.href.substring(0, window.location.href.lastIndexOf('/')) + "/preview.php",
    //                                          window.event.returnValue = false;
    //                                      return false;
                                          if ($.isEmptyObject(response.data) || $.isEmptyObject(response.audio)) {
                                              $("#errorMsg").html("快上传您的动图吧！<br>");
                                              return;
                                          }
                                          if (response.data) {
                                              var previewImages = [];
                                              $(response.data).each(function(i) {
                                                  previewImages[i] = this.url;
                                              })
                                              loadPreviewImage(previewImages);
                                          }
                                          if (response.audio) {
                                              var audioLink = response.audio.url;
                                              loadPreviewAudio(audioLink);
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
                          })();
                      </script>
                  </div>
                  <script type="text/javascript" src="js/wowslider.js"></script>
<!--                  <script type="text/javascript" src="plugins/slider/engine2/script.js"></script>-->
                    <script type="text/javascript"  src="<?="plugins/slider/engine".$engine."/script.js"?>" ></script>
                  <!-- End WOWSlider.com BODY section -->

              </div>
              </div>
          </div>
          <div class="mine-page-footer">
              <div class="my-support-section">
                  <div class="my-support-section-inner" align="center">
                      <div class="my-support-add"></div>
                      <div class="my-support-like"></div>
                      <div class="my-support-number"><span id="my-support-number">0</span></div>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <script>
      (function() {
          var isAppleMobileDevice = function () {
              return (/iphone|ipod|ipad|Macintosh/i.test(navigator.userAgent.toLowerCase()));
          };
          var playAudio = function() {
              var audio = $('.audio-preview-section').children('audio')[0];
              if (!audio.paused) {
                  audio.pause();
              }
              //                App.debug(audio.src);
              audio.play();
          };

          $.mobile.loading( 'hide');

          $(".ws-play-cover").unbind("click").bind("click", function(e) {
              return (function() {
                  $(".ws-play-cover").fadeOut("slow");
                  playAudio();
              })();
          });

          var readNumberOfSupport = function() {
              var openid = $.getUrlParam('openid');
              $.ajax({
                  async: false,
                  url: 'server/php/service/SupportService.php',
                  type: "POST",
                  data : JSON.stringify({method : 'POST', type : 'READ', openid : openid}),
                  dataType : "json",
                  timeout: 10000,
                  success: function (response) {
                      App.debug("response: " + JSON.stringify(response));
                      if (response.success) {
                          if (response.data && response.data.numberOfSupport) {
                              $("#my-support-number").text(response.data.numberOfSupport);
                          }
                          var audio = $('.audio-preview-section').children('audio')[0];

                          return false;
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
                  },
                  error : function (xhr, textStatus, errorThrown) {
                      App.debug("response: " + JSON.stringify(xhr));
                      $("#errorMsg").html("服务器繁忙，<br>请稍后再来！");
                      document.getElementById('messageMask').style.display='block';
                  }
              });
          };
          readNumberOfSupport();
      })();
  </script>
  </body>
</html>