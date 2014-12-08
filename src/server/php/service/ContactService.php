<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 14-10-29
 * Time: 上午11:19
 */
namespace com\beyond\mumm\music\user\service;

require_once(dirname(__FILE__) ."/../config/AppConfig.php");
require_once(dirname(__FILE__) . "/../common/CommonLib.php");
require_once(dirname(__FILE__) . "/../dao/ContactPDO.php");
require_once('MacService.php');

use com\beyond\mumm\music\config\AppConfig;
use com\beyond\common\CommonLib;
use com\beyond\mumm\music\profile\dao\ContactPDO;
use com\beyond\mumm\music\security\service\MacService;

session_start();
header('Content-Type: application/json; charset=utf-8');

$requestObj = json_decode(file_get_contents("php://input"));

$contactService = new ContactService();

$type = $requestObj->type;
$openid = $_SESSION['openid'];
if (empty($openid)) {
    $openid = $requestObj->openid;
}
$nickname = $requestObj->nickname;
$cellphone = $requestObj->cellphone;
if ($type === 'READ') {
    $contactService->readContact($requestObj);
} else if ($type === 'WRITE') {
    $contactService->updateContact($openid, $nickname, $cellphone);
}

class ContactService {


    public function readContact($openid) {
        if (empty($openid)) {
            echo json_encode(array('success'=>false, 'type'=>'error', 'code'=>'E002', 'request'=>$openid, 'data'=>null, 'message'=>"openid无效，请重试"));
            return;
        }
        $contactDao = new ContactPDO();
        $find = $contactDao->findByOpenId($openid);
        if ($find) {
            echo json_encode(array('success'=>true, 'type'=>'info', 'code'=>'I001', 'request'=>$openid, 'data'=>$find, 'message'=>"success"));
            return;
        } else {
            echo json_encode(array('success'=>false, 'type'=>'error', 'code'=>'E001', 'request'=>$openid, 'data'=>$find, 'message'=>"无法读取您选择的音乐信息</br>请返回重新选择"));
            return;
        }
    }

    public function readCategory($openid) {
        if (empty($openid)) {
            echo json_encode(array('success'=>false, 'type'=>'error', 'code'=>'E002', 'request'=>$openid, 'data'=>null, 'message'=>"openid无效，请重试"));
            return;
        }
        $contactDao = new ContactPDO();
        $find = $contactDao->findByOpenId($openid);
        return $find;
    }


    public function updateContact($openid, $nickname, $cellphone) {
//            CommonLib::my_debug("Update Contact OpenId ".$openid);
        if (empty($openid)) {
//            CommonLib::my_debug("Update User Return".$openid);
            echo json_encode(array('success'=>false, 'type'=>'error', 'code'=>'E002', 'request'=>$openid, 'data'=>null, 'message'=>"openid无效，请重试"));
            return;
        }
        $ret = 0;

        $contactDao = new ContactPDO();
        $find = $contactDao->findByOpenId($openid);
//        CommonLib::my_debug("Update Contact Find".json_encode($find));

        $macService = new MacService(PHP_OS);
        $macip = $macService->getIp();
        if (!$find) {
            $ret = $contactDao->insertFullSingleRow1($openid, $nickname, $cellphone, $macip);
            echo json_encode(array('success'=>true, 'type'=>'info', 'code'=>'I001', 'request'=>$openid, 'data'=>$ret, 'message'=>"add success"));
        } else {
            $ret = $contactDao->updateContact1($openid, $nickname, $cellphone, $macip);
            echo json_encode(array('success'=>true, 'type'=>'info', 'code'=>'I001', 'request'=>$openid, 'data'=>$ret, 'message'=>"update success"));
        }
//        CommonLib::my_debug("Update User Return".$ret);

        return;
    }

}
