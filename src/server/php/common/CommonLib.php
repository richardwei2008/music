<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-10-28
 * Time: 下午2:39
 */
namespace com\beyond\common;

require_once(dirname(__FILE__) . "/../config/AppConfig.php");

use com\beyond\mumm\music\config\AppConfig;

class CommonLib {

    public static function my_debug($debug) {
        if (AppConfig::ENVIRONMENT === 'sae' && AppConfig::DEBUG) {
            sae_debug($debug.'</br>');
        }
    }

    public static function encodeURIComponent($url) {
        return urlencode(iconv("gbk", "UTF-8", $url));
    }
}
