<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */
error_reporting(E_ALL | E_STRICT);
require('UploadHandler.php');
require('StorageService.php');

sae_debug("Start... ");
$options = array(
    'param_name' => 'files');
$upload = isset($_FILES[$options['param_name']]) ?
    $_FILES[$options['param_name']] : null;
sae_debug("Get Upload... ");
$form_data =$upload['tmp_name'];
sae_debug("Form Data ". $form_data);
$img_data = file_get_contents($form_data); //获取本地上传的图片数据

$storageService = new StorageService();
$url = $storageService->writeImage("png", $img_data);
sae_debug("URL ". $url);
//echo "<img src='$url' />";


//$uploadHandler = new UploadHandler();
//$uploadHandler->post();
