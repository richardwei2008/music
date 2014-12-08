<?php

require('Guid.php');
require_once(dirname(__FILE__) ."/../config/AppConfig.php");
require_once(dirname(__FILE__) . "/../common/CommonLib.php");

use com\beyond\common\CommonLib;

class StorageService {

    private $domain = "beyondwechattest";
    private $prefix = 'mumm_music_';

    private $imageTemplate = null;

    /**
     * @return null|\PDO
     */
    public function getImageTemplate()
    {
        if ($this->imageTemplate == null) {
            //从网络上抓取要合成的多张图片
            $this->imageTemplate = file_get_contents('http://beyondwechattest.sinaapp.com/music3/images/flash-bg.png');
        }
        return $this->imageTemplate;
    }


    public function write() {

        $storage = new SaeStorage();
        $destFileName = 'myfolder/write_test.txt';
        $content = 'Hello,I am from the method of write';
        $attr = array('encoding'=>'gzip');

        $result = $storage->write($this->domain, $destFileName, $content, -1, $attr, true);
    }

    public function writeRemoteImage() {
        $s = new SaeStorage();
        $guid = new Guid();
        $name = $this->prefix.$guid->guid().".png";
        $img = file_get_contents('http://beyondwechattest.sinaapp.com/music/images/sample.png');  //括号中的为远程图片地址
        $s->write($this->domain,  $name, $img );
    }

    public function writeImage($openid, $type, $img_data) {

        $imgBg = $this->getImageTemplate();
        //实例化SaeImage并取得最大一张图片的大小，稍后用于设定合成后图片的画布大小
        $imgTemplate = new SaeImage($imgBg);
        $size = $imgTemplate->getImageAttr();
        //清空$img数据
        $imgTemplate->clean();

        $img = new SaeImage();
        $img->setData($img_data);
        $img->resize(320);
        $img->improve(); //提高图片质量的函数
        $new_data = $img->exec(); // 执行处理并返回处理后的二进制数据

        $imgTemplate->setData( array(
            array( $imgBg, 0, 0, 1, SAE_TOP_LEFT ),
            array( $new_data, 0, 0, 1, SAE_CENTER_CENTER ),
        ) );
        $imgTemplate->composite($size[0], $size[1]);
        //输出图片
        $out_data = $imgTemplate->exec('jpg');
        $dir = $openid;
        $s = new SaeStorage();
        $guid = new Guid();
        $name = $this->prefix.$guid->guid().'.jpg';
        $s->write($this->domain, $dir."/".$name, $out_data); //将public修改为自己的storage 名称
        $url = $s->getUrl($this->domain, $dir."/".$name); //
        return $url;
    }

    public function writeImageBak($openid, $type, $img_data) {

        $guid = new Guid();
        $name = $this->prefix.$guid->guid().'.'.$type;
        $img = new SaeImage();
        $img->setData($img_data);
//        $img->resize(320, 504);
        $img->resize(320);
        $img->improve(); //提高图片质量的函数
        $new_data = $img->exec(); // 执行处理并返回处理后的二进制数据

        $dir = $openid;

        $s = new SaeStorage();
        $s->write($this->domain, $dir."/".$name, $new_data); //将public修改为自己的storage 名称
        $url = $s->getUrl($this->domain, $dir."/".$name); //
        return $url;
    }

    public function writeImageWithBackground() {
         //从网络上抓取要合成的多张图片
         $imgBg = file_get_contents('http://beyondwechattest.sinaapp.com/music3/images/flash-bg.png');
        //实例化SaeImage并取得最大一张图片的大小，稍后用于设定合成后图片的画布大小
         $img = new SaeImage($imgBg);
         $size = $img->getImageAttr();
         //清空$img数据
         $img->clean();

         $imgSampleData = file_get_contents('http://beyondwechattest.sinaapp.com/music3/images/samples/1.jpg');
         $imgSample = new SaeImage();
         $imgSample->setData($imgSampleData);
         $imgSample->resize(320);
         $imgSample->improve(); //提高图片质量的函数
         $new_data = $imgSample->exec(); // 执行处理并返回处理后的二进制数据
         //设定要用于合成的三张图片（如果重叠，排在后面的图片会盖住排在前面的图片）
         $img->setData( array(
             array( $imgBg, 0, 0, 1, SAE_TOP_LEFT ),
             array( $new_data, 0, 0, 1, SAE_CENTER_CENTER ),
         ) );

         //执行合成
         $img->composite($size[0], $size[1]);

         //输出图片
        $out_data = $img->exec('jpg');
        $s = new SaeStorage();
        $s->write($this->domain, "samples/composite.jpg", $out_data);
    }

//array getListByPath (string $domain, [string $path = NULL], [int $limit = 100], [int $offset = 0], [int $fold = true])
//string $domain: 存储域
//string $path: 目录地址
//int $limit: 单次返回数量限制，默认100，最大1000
//int $offset: 起始条数
//int $fold: 是否折叠目录
    public function deleteImage($openid) {
        $s = new SaeStorage();
        $ret = $s->getListByPath($this->domain, $openid, 20);
        CommonLib::my_debug("Deleting list...".json_encode($ret));
        $list = $ret['files'];
        foreach($list as $file) {
            CommonLib::my_debug("Deleting...".$file['Name']);
            $s->delete($this->domain, $file['fullName']);
        }
    }
}

//$storageService = new StorageService();
//$storageService->write();
//$storageService->writeImageWithBackground();


