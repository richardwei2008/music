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
require_once(dirname(__FILE__) . "/../dao/SupportPDO.php");
require_once('MacService.php');

use com\beyond\mumm\music\config\AppConfig;
use com\beyond\common\CommonLib;
use com\beyond\mumm\music\user\dao\SupportPDO;
use com\beyond\mumm\music\security\service\MacService;

session_start();
header('Content-Type: application/json; charset=utf-8');

$requestObj = json_decode(file_get_contents("php://input"));

$supportService = new SupportService();

$type = $requestObj->type;
$support_openid = $_SESSION['openid'];
if (empty($openid)) {
    $openid = $requestObj->openid;
}
if ($type === 'READ') {
    $supportService->readSupport($openid);
} else if ($type === 'WRITE') {
    $supportService->updateSupport($openid, $support_openid);
} else if ($type === 'MORE') {
    $supportService->topSupport();
}

class SupportService {


    public function readSupport($openid) {
        if (empty($openid)) {
            echo json_encode(array('success'=>false, 'type'=>'error', 'code'=>'E002', 'request'=>$openid, 'data'=>null, 'message'=>"openid无效，请重试"));
            return;
        }
        $supportDao = new SupportPDO();
        $find = $supportDao->findNumberOfSupport($openid);
        if ($find) {
            echo json_encode(array('success'=>true, 'type'=>'info', 'code'=>'I001', 'request'=>$openid, 'data'=>$find, 'message'=>"success"));
            return;
        } else {
            echo json_encode(array('success'=>false, 'type'=>'error', 'code'=>'E001', 'request'=>$openid, 'data'=>$find, 'message'=>"无法读取您的信息</br>请返回重新登录"));
            return;
        }
    }

    public function updateSupport($openid, $support_openid) {
//            CommonLib::my_debug("Update Support OpenId ".$openid);
        if (empty($openid)) {
//            CommonLib::my_debug("Update User Return".$openid);
            echo json_encode(array('success'=>false, 'type'=>'error', 'code'=>'E002', 'request'=>$openid, 'data'=>null, 'message'=>"openid无效，请重试"));
            return;
        }
        $ret = 0;

        $supportDao = new SupportPDO();
        $find = $supportDao->findNumberOfSupportBetweenBySupportOpenid($openid, $support_openid, date("Y-m-d"), date("Y-m-d",strtotime("+1 day")));
//        CommonLib::my_debug("Update Support Find".json_encode($find));

        $macService = new MacService(PHP_OS);
        $macip = $macService->getIp();
        if (null !== $find && $find->numberOfSupport > 0) {
            echo json_encode(array('success'=>false, 'type'=>'warn', 'code'=>'E003', 'request'=>$openid, 'data'=>$find, 'message'=>"你今天已经赞过了，<br>请明天再继续吧！"));
        } else {
            $ret = $supportDao->insertSingleRow($openid, $support_openid, $macip);
            $find = $supportDao->findNumberOfSupport($openid);
            echo json_encode(array('success'=>true, 'type'=>'info', 'code'=>'I001', 'request'=>$openid, 'data'=>$find, 'message'=>"add success"));
        }
//        CommonLib::my_debug("Update User Return".$ret);
        return;
    }

    public function topSupport() {
        $supportDao = new SupportPDO();
        $find = $supportDao->findMore();
        if ($find) {
            echo json_encode(array('success'=>true, 'type'=>'info', 'code'=>'I001', 'request'=>null, 'data'=>$find, 'message'=>"success"));
            return;
        } else {
            echo json_encode(array('success'=>false, 'type'=>'error', 'code'=>'E001', 'request'=>null, 'data'=>$find, 'message'=>"无法读取您的信息</br>请返回重新登录"));
            return;
        }
    }
}
