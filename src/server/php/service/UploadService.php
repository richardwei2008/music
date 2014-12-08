<?php session_start();
require_once(dirname(__FILE__) ."/../config/AppConfig.php");
require_once(dirname(__FILE__) ."/../dao/ImagePDO.php");
require_once(dirname(__FILE__) . "/../common/CommonLib.php");
require_once('StorageService.php');
require_once('MacService.php');

use com\beyond\common\CommonLib;
use com\beyond\mumm\music\config\AppConfig;
use com\beyond\mumm\music\meida\dao\ImagePDO;
use com\beyond\mumm\music\security\service\MacService;
header('Content-Type: application/json; charset=utf-8');
function bytesToSize1024($bytes, $precision = 2) {
    $unit = array('B','KB','MB');
    return @round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), $precision).' '.$unit[$i];
}

function get_extension($file_name){
    $ext = explode('.', $file_name);
    $ext = array_pop($ext);
    return strtolower($ext);
}



function process($openid) {
    $options = array(
        'param_name1' => 'fileupload1',
        'param_name2' => 'fileupload2',
        'param_name3' => 'fileupload3',
        'param_name4' => 'fileupload4',
        'param_name5' => 'fileupload5');
    $imageStack = array();

    cleanImage($openid);
    foreach($options as $v) {
        $upload = isset($_FILES[$v]) ? $_FILES[$v] : null;
        if ($upload !== null) {
            $sFileName = $upload['name'];
            $sFileType = $upload['type'];
            $sFileSize = bytesToSize1024($upload['size'], 1);

            CommonLib::my_debug("Get Upload... ".$sFileName);
            CommonLib::my_debug("File Type... ".$sFileType);
            CommonLib::my_debug("Size: " + $sFileSize);
            if ($sFileSize > 0) {
                if (AppConfig::ENVIRONMENT === 'sae') {
                    $url = storageImage($openid, $upload, $sFileName, $sFileType);
                    array_push($imageStack, array('name'=>$sFileName, 'dir'=>$openid, 'url'=>$url));
                } else {
                    uploadImage($upload);
                }
            }
        }
    }
    if (AppConfig::ENVIRONMENT === 'sae') {
        $imagePDO = new ImagePDO();
        $imagePDO->invalidImage($openid);
        $macService = new MacService(PHP_OS);
        $macip = $macService->getIp();
        foreach($imageStack as $img) {
            $imagePDO->insertFullSingleRow($openid, $img['name'], $img['dir'], $img['url'], $macip);
        }
    }
}


function uploadImage($fileupload) {
    if(move_uploaded_file($fileupload['tmp_name'], AppConfig::UPLOAD_DIR.$fileupload['name'])){
        return;
    }
}

function cleanImage($openid) {
    $storageService = new StorageService();
    $storageService->deleteImage($openid);
}

function storageImage($openid, $upload, $fileName, $fileType) {
    $form_data =$upload['tmp_name'];
    CommonLib::my_debug("Form Data ". $form_data);
    $img_data = file_get_contents($form_data); //获取本地上传的图片数据

    $storageService = new StorageService();
    // $url = $storageService->writeImage("png", $img_data);
    $format = get_extension($fileName);
    CommonLib::my_debug("File Format... ".$format);
    $url = $storageService->writeImage($openid, $format, $img_data);
    CommonLib::my_debug("URL ". $url);
    return $url;
}

$openid = $_SESSION['openid'];
CommonLib::my_debug("Upload OpenId " + $openid);

if (AppConfig::DEBUG) {
    process($openid);
    echo ("上传成功！");
} else {
    try {
        process($openid);
        echo ("上传成功！");
    } catch (Exception $e) {
        echo ("上传失败！");
        exit();
    }
}





