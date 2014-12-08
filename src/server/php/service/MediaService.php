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
require_once(dirname(__FILE__) . "/../dao/ImagePDO.php");
require_once(dirname(__FILE__) . "/../dao/AudioPDO.php");
require_once('MacService.php');
use com\beyond\common\CommonLib;
use com\beyond\mumm\music\meida\dao\ImagePDO;
use com\beyond\mumm\music\meida\dao\AudioPDO;



session_start();
header('Content-Type: application/json; charset=utf-8');

$requestObj = json_decode(file_get_contents("php://input"));
//CommonLib::my_debug("Audio Service Request ".json_encode($requestObj));
$mediaService = new MediaService();

//$type = $requestObj->type;
$openid = $requestObj->openid;
//$openid = $_SESSION['openid'];
//if (empty($openid)) {
//
//}

$mediaService->readMedia($openid);


class MediaService {

    public function readMedia($openid) {
        if (empty($openid)) {
            echo json_encode(array('success'=>false, 'type'=>'error', 'code'=>'E002', 'request'=>$openid, 'data'=>null, 'message'=>"openid无效，请重试"));
            return;
        }
        $imageDao = new ImagePDO();
        $findImages = $imageDao->findByOpenId($openid);
        $audioDao = new AudioPDO();
        $findAudio = $audioDao->findByOpenId($openid);
        if ($findImages && $findAudio) {
            echo json_encode(array('success'=>true, 'type'=>'info', 'code'=>'I001', 'request'=>$openid, 'data'=>$findImages, 'audio'=>$findAudio, 'message'=>"success"));
            return;
        } else {
            echo json_encode(array('success'=>false, 'type'=>'error', 'code'=>'E001', 'request'=>$openid, 'data'=>$findImages, 'audio'=>$findAudio, 'message'=>"无法读取您选择的图片和音乐信息</br>请返回重新选择"));
            return;
        }
    }

}
