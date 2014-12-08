<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 14-10-29
 * Time: 上午11:19
 */


namespace com\beyond\mumm\music\user\service;
require_once(dirname(__FILE__) . "/../dao/UserPDO.php");
require_once(dirname(__FILE__) ."/../config/AppConfig.php");
require_once(dirname(__FILE__) . "/../common/CommonLib.php");
use com\beyond\common\CommonLib;
use com\beyond\mumm\music\user\dao\UserPDO;

class UserService {

    public function updateUser($openid, $userArray) {
        CommonLib::my_debug("Update User OpenId ".$openid);
        CommonLib::my_debug("Update User ".json_encode($userArray));
        if (empty($openid)) {
            CommonLib::my_debug("Update User Return".$openid);
            return 0;
        }
        $ret = 0;

        $subscribe = 0;
        $nickname = $userArray['nickname'];
        $sex = $userArray['sex'];
        $language = $userArray['language'];
        $city = $userArray['city'];
        $province = $userArray['province'];
        $country = $userArray['country'];
        $headimgurl = $userArray['headimgurl'];
        $subscribetime = 0;

        CommonLib::my_debug("Update User".$subscribe);
        CommonLib::my_debug("Update User".$nickname);
        CommonLib::my_debug("Update User".$sex);
        CommonLib::my_debug("Update User".$language);
        CommonLib::my_debug("Update User".$city);
        CommonLib::my_debug("Update User".$province);
        CommonLib::my_debug("Update User".$country);
        CommonLib::my_debug("Update User".$headimgurl);
        CommonLib::my_debug("Update User".$subscribetime);

        $userDao = new UserPDO();
        $find = $userDao->findByOpenId($openid);
        CommonLib::my_debug("Update User Find".json_encode($find));
        if ($find) {
            $ret = $userDao->updateUser($openid, $subscribe, $nickname, $sex, $language, $city, $province, $country, $headimgurl, $subscribetime);
        } else {
            $ret = $userDao->insertFullSingleRow($openid, $subscribe, $nickname, $sex, $language, $city, $province, $country, $headimgurl, $subscribetime);
        }
        CommonLib::my_debug("Update User Return".$ret);
        return $ret;
    }

}

//$test = new UserService();
//$openid =  "oxqECj13-L8dz--q6dd9Z34ouTfc";
//$userArray = array(
//    'openid'=>'oxqECj13-L8dz--q6dd9Z34ouTfc',
//    'nickname'=>'Richard',
//    'sex'=>1,
//    'language'=>'zh_CN',
//    'city'=>'黄浦',
//    'province'=>'上海',
//    'country'=>'中国',
//    'headimgurl'=>"http://wx.qlogo.cn/mmopen/DmhcK3ycJ6qvf6pFxumoqT8WFdAGl5fNV5dLrzlqnoyEuqEcTLgr03HOOD9ZswwQahwLjWLTM191WzPQib9H0kPpKE1RF7bCZ/0");
//$ret = $test->updateUser($openid, $userArray);
//CommonLib::my_debug("Test Update User Return".$ret);