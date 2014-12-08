<?php

namespace com\beyond\mumm\music\config;
class AppConfig {
    const ENVIRONMENT = "sae"; // local
	const TEST = true;
    const DEBUG = false;
    const UPLOAD_DIR = 'uploads/';
    const LOGIN_URL = 'http://beyondwechattest.sinaapp.com/music2/server/php/func/wx_login.php';
    const AUTH_URL = 'http://beyondwechattest.sinaapp.com/music2/server/php/func/auth_callback.php';
    const TEST_URL = 'http://beyondwechattest.sinaapp.com/music2/server/php/service/testB.php';
    const HOME_URL = 'http://beyondwechattest.sinaapp.com/music2/index.php';
	const MINE_URL = 'http://beyondwechattest.sinaapp.com/music2/mine.php';
    const PREVIEW_URL = 'http://beyondwechattest.sinaapp.com/music2/preview.php';

    const APP_ID = "wxc63c757bdae5dd41";
    const APP_SECRET = "05fc96a2887de802e694252b040fc53f";
} 