
(function () {
	if (document.addEventListener) {
		document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
	} else if (document.attachEvent) {
		document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
		document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
	}
    function onBridgeReady() {
        // 发送给好友;
        WeixinJSBridge.on('menu:share:appmessage', function (argv) {
            WeixinJSBridge.invoke('sendAppMessage', makeContext(), function (res) {});
        });
        // 分享到朋友圈;
        WeixinJSBridge.on('menu:share:timeline', function (argv) {
            WeixinJSBridge.invoke('shareTimeline', makeContext(), function (res) {});
        });
        WeixinJSBridge.call('showOptionMenu');
    };
    makeContext = function() {
        return (function() {
            var context = {
                "img_url" : window.location.href.substring(0, window.location.href.lastIndexOf('/'))  + '/images/share.jpg',
                "link" : "http://mp.weixin.qq.com/s?__biz=MzA3MDE3NzMwOQ==&mid=202431778&idx=1&sn=4df77e56db354ec8937d8ad32dff83c1#rd", // window.location.href.substring(0, window.location.href.lastIndexOf('/')) + "/index.php",
                "desc" : "G.H.MUMM-Celebrating F1rst",
                "title" : "独乐何不众乐？欢庆岂能一人独享！"
            };
            var openid = $.getUrlParam('openid');
            var sid = $.getUrlParam('sid');
            if (!$.isEmptyObject(sid) || window.location.href.indexOf("preview.php") > 0) {
                var shareLink = window.location.href.substring(0, window.location.href.lastIndexOf('/')) + "/share.php" + "?" + "sid=" + openid;
//                alert("shareLink " + shareLink);
                context.link = shareLink;
            }
//            alert("Context " + JSON.stringify(context));
            return context;
        })();
    };
})();

function isWeiXin() {
		var ua = window.navigator.userAgent.toLowerCase();
		if (ua.match(/MicroMessenger/i) == 'micromessenger') {
			return true;
		} else {
			return false;
		}
	};

function addWxContact(wxid) {      
	if (typeof WeixinJSBridge == 'undefined') return false;          
		WeixinJSBridge.invoke('addContact', {              
		webtype: '1',              
		username: 'gh_e5430c6431e7'          
	},  function(d) {             
		 // 返回d.err_msg取值，d还有一个属性是err_desc
            // add_contact:cancel 用户取消
            // add_contact:fail　关注失败
            // add_contact:ok 关注成功
            // add_contact:added 已经关注
            WeixinJSBridge.log(d.err_msg);
            cb && cb(d.err_msg);
			});
};
