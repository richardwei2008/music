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
require_once('MacService.php');
use com\beyond\common\CommonLib;
use com\beyond\mumm\music\meida\dao\ImagePDO;
use com\beyond\mumm\music\security\service\MacService;


session_start();
header('Content-Type: application/json; charset=utf-8');

$requestObj = json_decode(file_get_contents("php://input"));
//CommonLib::my_debug("Audio Service Request ".json_encode($requestObj));
$imageService = new ImageService();

//$type = $requestObj->type;
$openid = $_SESSION['openid'];
if (empty($openid)) {
    $openid = $requestObj->openid;
}

$imageService->readImages($openid);


class ImageService {

    public function readImages($openid) {
        if (empty($openid)) {
            echo json_encode(array('success'=>false, 'type'=>'error', 'code'=>'E002', 'request'=>$openid, 'data'=>null, 'message'=>"openid无效，请重试"));
            return;
        }
        $imageDao = new ImagePDO();
        $find = $imageDao->findByOpenId($openid);
        if ($find) {
            echo json_encode(array('success'=>true, 'type'=>'info', 'code'=>'I001', 'request'=>$openid, 'data'=>$find, 'message'=>"success"));
            return;
        } else {
            echo json_encode(array('success'=>false, 'type'=>'error', 'code'=>'E001', 'request'=>$openid, 'data'=>$find, 'message'=>"无法读取您选择的图片信息</br>请返回重新选择"));
            return;
        }
    }
}
