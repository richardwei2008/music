<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 14-10-29
 * Time: 上午11:19
 */


namespace com\beyond\mumm\music\media\service;

require_once(dirname(__FILE__) ."/../config/AppConfig.php");
require_once(dirname(__FILE__) . "/../common/CommonLib.php");
require_once(dirname(__FILE__) . "/../dao/AudioPDO.php");
require_once('MacService.php');
use com\beyond\common\CommonLib;
use com\beyond\mumm\music\meida\dao\AudioPDO;
use com\beyond\mumm\music\security\service\MacService;


session_start();
header('Content-Type: application/json; charset=utf-8');

$requestObj = json_decode(file_get_contents("php://input"));
//CommonLib::my_debug("Audio Service Request ".json_encode($requestObj));
$audioService = new AudioService();

$type = $requestObj->type;
$openid = $_SESSION['openid'];
if (empty($openid)) {
    $openid = $requestObj->openid;
}
$name = $requestObj->name;
$audioIndex = $requestObj->audioIndex;
if ($type === 'READ') {
    $audioService->readAudio($openid);
} else if ($type === 'WRITE') {
    $audioService->updateAudio($openid, $name, $audioIndex);
}

class AudioService {

    private $audioArray = null;

    public function getAudios() {
        if ($this->audioArray === null) {
            $this->audioArray = array(
//                'http://com-beyond2005-test.qiniudn.com/mumm_music_MUMM_DG_Music-part1.mp3',
//                'http://com-beyond2005-test.qiniudn.com/mumm_music_MUMM_DG_Music-part2.mp3',
//                'http://com-beyond2005-test.qiniudn.com/mumm_music_MUMM_DG_Music-part3.mp3',
//                'http://com-beyond2005-test.qiniudn.com/mumm_music_MUMM_DG_Music-part4.mp3'
                'http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part1.m4a',
                'http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part2.m4a',
                'http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part3.m4a',
                'http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part4.m4a',
                'http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part5.m4a',
                'http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part6.m4a',
                'http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part7.m4a',
                'http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part8.m4a',
                'http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part9.m4a',
                'http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part10.m4a',
                'http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part11.m4a',
                'http://com-beyond2005-test.qiniudn.com/mumm_music_David_Guetta-Dangerous-Part12.m4a'
            );
        }
        return $this->audioArray;
    }

    public function readAudio($openid) {
        if (empty($openid)) {
            echo json_encode(array('success'=>false, 'type'=>'error', 'code'=>'E002', 'request'=>$openid, 'data'=>null, 'message'=>"openid无效，请重试"));
            return;
        }
        $audioDao = new AudioPDO();
        $find = $audioDao->findByOpenId($openid);
        if ($find) {
            echo json_encode(array('success'=>true, 'type'=>'info', 'code'=>'I001', 'request'=>$openid, 'data'=>$find, 'message'=>"success"));
            return;
        } else {
            echo json_encode(array('success'=>false, 'type'=>'error', 'code'=>'E001', 'request'=>$openid, 'data'=>$find, 'message'=>"无法读取您选择的音乐信息</br>请返回重新选择"));
            return;
        }
    }

    public function updateAudio($openid, $name, $audioIndex) {
//        CommonLib::my_debug("Update Audio OpenId ".$openid);
        if (empty($openid)) {
//            CommonLib::my_debug("Update User Return".$openid);
            echo json_encode(array('success'=>false, 'type'=>'error', 'code'=>'E002', 'request'=>$openid, 'data'=>null, 'message'=>"openid无效，请重试"));
            return;
        }
        $ret = 0;

        $audioDao = new AudioPDO();
        $find = $audioDao->findByOpenId($openid);
//        CommonLib::my_debug("Update Audio Find ".json_encode($find));
        $audioList = $this->getAudios();
        $url = $audioList[$audioIndex];
        $macService = new MacService(PHP_OS);
        $macip = $macService->getIp();
        if (!$find) {
            $ret = $audioDao->insertFullSingleRow($openid, $name, $url, $macip);
            echo json_encode(array('success'=>true, 'type'=>'info', 'code'=>'I001', 'request'=>$openid, 'data'=>$ret, 'message'=>"add success"));
        } else {
            $ret = $audioDao->updateAudio($openid, $name, $url, $macip);
            echo json_encode(array('success'=>true, 'type'=>'info', 'code'=>'I001', 'request'=>$openid, 'data'=>$ret, 'message'=>"update success"));
        }
//        CommonLib::my_debug("Update User Return".$ret);

        return;
    }

}
