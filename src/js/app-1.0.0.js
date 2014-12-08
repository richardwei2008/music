App = {
    DEBUG : false,
    debug : function(obj) {
        return function() {
            if (App.DEBUG) {
                window.alert(obj);
            }
        }(obj);
    },

    checkMobile : function(sMobile) {
        if(!(/^1[3|4|5|8][0-9]\d{4,8}$/.test(sMobile))){
            return false;
        }
        return true;
    },
    redirectTo : function(relativeTargetUri, openid) {
        return (function() {
//            var redirectToUrl = App.formatRedirectUriWithGlobalUserParam(globalUser, relativeTargetUri);
            var redirectToUrl = window.location.href.substring(0, window.location.href.lastIndexOf('/')) + "/" + relativeTargetUri;
            if (!$.isEmptyObject(openid)) {
                redirectToUrl = redirectToUrl + "?openid=" + openid;
            }
            App.debug("Redirect: " + redirectToUrl);
            window.location.href = redirectToUrl;
            window.event.returnValue = false;
            return false;
        }());

    },
    init : function() {
        $(".music-item").unbind("click").bind("click", function(e) {
            $(".music-item").children("div").children("audio").each(function(i) {
                if (!this.paused) {
                    this.pause();
                    this.currentTime = 0.0;
                }
            });
            $(".music-item").removeClass("music-item-active");
            $(this).addClass("music-item-active");
            console.log("Index: " +  $(this).index());
            $(this).children("div").children("audio")[0].play();
        });

        $(".preview-chooser").unbind("click").bind("click", function(e) {
            var oImage = $(this).children("img")[0];
            magnify(oImage);
        });

        $(".fileupload").unbind("change").bind("change", function(e) {
            fileSelected(this);
        });

        $("#button-enter").unbind("click").bind("click", function(e) {
            App.debug("Enter clicked");
            var openid = $.getUrlParam('openid');
            App.redirectTo("enter.php", openid);
        });

        $("#button-upload").unbind("click").bind("click", function(e) {
            startUploading();
        });

        $("#button-join").unbind("click").bind("click", function(e) {
            var openid = $.getUrlParam('openid');
            App.redirectTo("index.php", "");
        });


        $("#button-music").unbind("click").bind("click", function(e) {
            var audio = $(".music-item-active").children(".audio-control").children("audio")[0];
            if (!audio.paused) {
                audio.pause();
                audio.currentTime = 0.0;
            }
            audio.play();
            window.location.href = "#view-music";
            window.event.returnValue = false;
            return false;
        });
        // TODO
        $("#button-mine").unbind("click").bind("click", function(e) {
            var openid = $.getUrlParam('openid');
            App.redirectTo("mine.php", openid);
            return false;
        });

        $("#button-more").unbind("click").bind("click", function(e) {
            var openid = $.getUrlParam('openid');
            App.redirectTo("top.php", openid);
            return false;
        });

        $("#button-submit").unbind("click").bind("click", function(e) {
            var audioIndex =  $(".music-item-active").index();
            var name = $(".music-item-active").children(".audio-control").children("audio").attr("src");
            var openid = $.getUrlParam('openid');
            window.location.href = '#view-submit';
            $.ajax({
                async: false,
                url: 'server/php/service/AudioService.php',
                type: "POST",
                data : JSON.stringify({method : 'POST', type : 'WRITE', openid : openid, name : name,  audioIndex : audioIndex}),
                dataType : "json",
                timeout: 10000,
                success: function (response) {
                    App.debug("response: " + JSON.stringify(response));
                    if (response.success) {
                        $(".music-item-active").children("div").children("audio").each(function(i) {
                            if (!this.paused) {
                                this.pause();
                                this.currentTime = 0.0;
                            }
                        });
                        window.location.href = '#view-submit';
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
        });

//        var loadPreviewImage = function() {
//            return function() {
//                var previewImages = $('.preview-img').filter(function() {return this.src !== ''});
//                var size = previewImages.length;
//                $('.flash-images').each(function(i) {
//                    this.src = previewImages[i % size].src;
//                })
//                $('.flash-tooltips').each(function(i) {
//                    this.src = previewImages[i % size].src;
//                })
//            }();
//        };
        $("#button-preview").unbind("click").bind("click", function(e) {
//            loadPreviewImage();
            var nickname = $('#nickname').val();
            var cellphone = $('#cellphone').val();
            if ($.isEmptyObject(nickname)) {
                alert("请填写昵称");
                return;
            }
            if ($.isEmptyObject(cellphone) || cellphone.length !== 11 || !App.checkMobile(cellphone)) {
                alert("请正确填写11位手机号码");
                return;
            }
            var openid = $.getUrlParam('openid');
            $.ajax({
                async: false,
                url: 'server/php/service/ContactService.php',
                type: "POST",
                data : JSON.stringify({method : 'POST', type : 'WRITE', openid : openid, nickname : nickname,  cellphone : cellphone}),
                dataType : "json",
                timeout: 10000,
                success: function (response) {
                    App.debug("response: " + JSON.stringify(response));
                    if (response.success) {
//                        var redirectUrl = window.location.href.substring(0, window.location.href.lastIndexOf('/')) + "/preview.php?openid=" + openid;
//                        App.debug(redirectUrl);
//                        window.location.href = redirectUrl;
//                        window.event.returnValue = false;
                        App.redirectTo("preview.php", openid);
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

        });

        var iMaxFilesize = 4194304; // 4MB
//        var iMaxFilesize = 2097152; // 2MB
//        var iMaxFilesize = 524288; // 512KB
        var bytesToSize = function (bytes) {
            var sizes = ['Bytes', 'KB', 'MB'];
            if (bytes == 0) return 'n/a';
            var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
            return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
        };
        var fileSelected = function (that) {
            // hide different warnings
//            document.getElementById('upload_response').style.display = 'none';
//            document.getElementById('error').style.display = 'none';
//            document.getElementById('error2').style.display = 'none';
//            document.getElementById('abort').style.display = 'none';
//            document.getElementById('warnsize').style.display = 'none';

            // get selected file element
//            var oFile = document.getElementById('fileupload1').files[0];
            var oFile = document.getElementById(that.id).files[0];

            // filter for image files
            var rFilter = /^(image\/bmp|image\/gif|image\/jpeg|image\/png|image\/tiff)$/i;
            if (! rFilter.test(oFile.type)) {
//                document.getElementById('error').style.display = 'block';
                return;
            }

            // little test for filesize
            if (oFile.size > iMaxFilesize) {
                document.getElementById('warnsize').style.display = 'block';
                return;
            }

            // get preview element
            var oImage = document.getElementById('preview' + that.id.substr(that.id.length - 1));
            var pImage = document.getElementById('largePreview');
            // prepare HTML5 FileReader
            var oReader = new FileReader();
            oReader.onload = function(e){

                // e.target.result contains the DataURL which we will use as a source of the image
                oImage.src = e.target.result;

                oImage.onload = function () { // binding onload event

                    // we are going to display some custom image information here
                    sResultFileSize = bytesToSize(oFile.size);
//                    document.getElementById('fileinfo').style.display = 'block';
//                    document.getElementById('filename').innerHTML = 'Name: ' + oFile.name;
//                    document.getElementById('filesize').innerHTML = 'Size: ' + sResultFileSize;
//                    document.getElementById('filetype').innerHTML = 'Type: ' + oFile.type;
//                    document.getElementById('filedim').innerHTML = 'Dimension: ' + oImage.naturalWidth + ' x ' + oImage.naturalHeight;
                };
                pImage.src = oImage.src;
            };

            // read selected file as DataURL
            oReader.readAsDataURL(oFile);
        };
        var magnify = function(oImage) {
            var largePreview = document.getElementById('largePreview');
            largePreview.src = oImage.src;
        };

        /** file upload */
        var secondsToTime = function (secs) { // we will use this function to convert seconds in normal time format
            var hr = Math.floor(secs / 3600);
            var min = Math.floor((secs - (hr * 3600))/60);
            var sec = Math.floor(secs - (hr * 3600) -  (min * 60));

            if (hr < 10) {hr = "0" + hr; }
            if (min < 10) {min = "0" + min;}
            if (sec < 10) {sec = "0" + sec;}
            if (hr) {hr = "00";}
            return hr + ':' + min + ':' + sec;
        };

        var iBytesUploaded = 0;
        var iBytesTotal = 0;
        var iPreviousBytesLoaded = 0;
        var oTimer = 0;
        var sResultFileSize = '';

        var startUploading = function () {
            var numberOfUpload = $(".preview-img").filter(function() {return this.src !== ''}).size();

            if (!$.isNumeric(numberOfUpload) || numberOfUpload < 3) {
                $("#errorMsg").html("请至少选择3副图片");
                document.getElementById('messageMask').style.display='block';
                return;
            }
            $.mobile.loading( 'show');
            // cleanup all temp states
            iPreviousBytesLoaded = 0;
            document.getElementById('upload_response').style.display = 'none';
            document.getElementById('error').style.display = 'none';
            document.getElementById('error2').style.display = 'none';
            document.getElementById('abort').style.display = 'none';
            document.getElementById('warnsize').style.display = 'none';
            document.getElementById('progress_percent').innerHTML = '';
            var oProgress = document.getElementById('progress');
            oProgress.style.display = 'block';
            oProgress.style.width = '0px';

            // get form data for POSTing
            //var vFD = document.getElementById('upload_form').getFormData(); // for FF3
            var vFD = new FormData(document.getElementById('upload_form'));

            // create XMLHttpRequest object, adding few event listeners, and POSTing our data
            var oXHR = new XMLHttpRequest();
            oXHR.upload.addEventListener('progress', uploadProgress, false);
            oXHR.addEventListener('load', uploadFinish, false);
            oXHR.addEventListener('error', uploadError, false);
            oXHR.addEventListener('abort', uploadAbort, false);
            oXHR.open('POST', 'server/php/service/UploadService.php');
            oXHR.send(vFD);

            // set inner timer
            oTimer = setInterval(doInnerUpdates, 300);
        };

        var doInnerUpdates = function () { // we will use this function to display upload speed
            var iCB = iBytesUploaded;
            var iDiff = iCB - iPreviousBytesLoaded;

            // if nothing new loaded - exit
            if (iDiff == 0)
                return;

            iPreviousBytesLoaded = iCB;
            iDiff = iDiff * 2;
            var iBytesRem = iBytesTotal - iPreviousBytesLoaded;
            var secondsRemaining = iBytesRem / iDiff;

            // update speed info
            var iSpeed = iDiff.toString() + 'B/s';
            if (iDiff > 1024 * 1024) {
                iSpeed = (Math.round(iDiff * 100/(1024*1024))/100).toString() + 'MB/s';
            } else if (iDiff > 1024) {
                iSpeed =  (Math.round(iDiff * 100/1024)/100).toString() + 'KB/s';
            }

            document.getElementById('speed').innerHTML = iSpeed;
            document.getElementById('remaining').innerHTML = '| ' + secondsToTime(secondsRemaining);
        };

        var uploadProgress = function (e) { // upload process in progress
            if (e.lengthComputable) {
                iBytesUploaded = e.loaded;
                iBytesTotal = e.total;
                var iPercentComplete = Math.round(e.loaded * 100 / e.total);
                var iBytesTransfered = bytesToSize(iBytesUploaded);

                document.getElementById('progress_percent').innerHTML = iPercentComplete.toString() + '%';
                document.getElementById('progress').style.width = (iPercentComplete * 4).toString() + 'px';
//                document.getElementById('b_transfered').innerHTML = iBytesTransfered;
                if (iPercentComplete == 100) {
                    var oUploadResponse = document.getElementById('upload_response');
                    oUploadResponse.innerHTML = '<h1>请等待...上传中</h1>';
                    oUploadResponse.style.display = 'block';
                }
            } else {
                document.getElementById('progress').innerHTML = '无法完成上传';
            }
        };

        var uploadFinish = function (e) { // upload successfully finished
            var oUploadResponse = document.getElementById('upload_response');
            oUploadResponse.innerHTML = e.target.responseText;
            oUploadResponse.style.display = 'block';

            document.getElementById('progress_percent').innerHTML = '100%';
//            document.getElementById('progress').style.width = '400px';
            document.getElementById('filesize').innerHTML = sResultFileSize;
//            document.getElementById('remaining').innerHTML = '| 00:00:00';

            clearInterval(oTimer);
            $.mobile.loading( 'hide');

            $("#button-upload").css("display", "none");
            $("#button-music").css("display", "block");
        };

        var uploadError = function (e) { // upload error
            document.getElementById('error2').style.display = 'block';
            clearInterval(oTimer);
            $.mobile.loading( 'hide');
        };

        var uploadAbort = function (e) { // upload abort
            document.getElementById('abort').style.display = 'block';
            clearInterval(oTimer);
            $.mobile.loading( 'hide');
        };

    }
};


(function () {
    if (document.addEventListener) {
        document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
    } else if (document.attachEvent) {
        document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
        document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
    }

    function onBridgeReady() {

    };
})();


$(document).ready(function() {
    App.init();

    $(".loader").fadeOut("slow");
});
