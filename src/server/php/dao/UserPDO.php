<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 14-10-28
 * Time: 下午10:53
 */

namespace com\beyond\mumm\music\user\dao;

require_once(dirname(__FILE__) ."/../database/DbConfig.php");

use \PDO;
use \PDOException;
use com\beyond\mumm\music\config\DbConfig;


class UserPDO {
    private $conn = null;

    /**
     * @param null|\PDO $conn
     */
    public function setConn($conn)
    {
        return $this->conn;
    }

    /**
     * @return null|\PDO
     */
    public function getConn()
    {
        if ($this->conn == null) {
            $connectionString = sprintf("mysql:host=%s;dbname=%s",
                DbConfig::DB_HOST,
                DbConfig::DB_NAME);
            try {
                $this->conn = new PDO($connectionString,
                    DbConfig::DB_USER,
                    DbConfig::DB_PASSWORD,
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8")
                );
                //            echo "Connected successfully.";
            } catch (PDOException $pe) {
                die($pe->getMessage());
            }
        }
        return $this->conn;
    }

    /**
     * Open the database connection
     */
    public function __construct(){
    }

    public function insertSingleRow($openid){
        try {
            $mumm_music_user = array(
                ':openid' => $openid);

            $sql = 'INSERT INTO mumm_music_user(openid)
                    VALUES(:openid)';
            $q = $this->getConn()->prepare($sql);

            if($q->execute($mumm_music_user)) {
                return $this->getConn()->lastInsertId();
            } else {
                return 0;
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }

    public function insertFullSingleRow($openid, $subscribe, $nickname, $sex, $language, $city, $province, $country, $headimgurl, $subscribetime){
        try {
            $mumm_music_user = array(
                ':openid' => $openid,
                ':subscribe' => $subscribe,
                ':nickname' => $nickname,
                ':sex' => $sex,
                ':language' => $language,
                ':city' => $city,
                ':province' => $province,
                ':country' => $country,
                ':headimgurl' => $headimgurl,
                ':subscribetime' => $subscribetime
            );

            $sql = 'INSERT INTO mumm_music_user(openid, subscribe, nickname, sex, language, city, province, country, headimgurl, subscribetime)
                    VALUES(:openid, :subscribe, :nickname, :sex, :language, :city, :province, :country, :headimgurl, :subscribetime)';
            $q = $this->getConn()->prepare($sql);

            if($q->execute($mumm_music_user)) {
                return $this->getConn()->lastInsertId();
            } else {
                return 0;
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }

    public function updateUser($openid, $subscribe, $nickname, $sex, $language, $city, $province, $country, $headimgurl, $subscribetime){
        try {
            $mumm_music_user = array(
                ':openid' => $openid,
                ':subscribe' => $subscribe,
                ':nickname' => $nickname,
                ':sex' => $sex,
                ':language' => $language,
                ':city' => $city,
                ':province' => $province,
                ':country' => $country,
                ':headimgurl' => $headimgurl,
                ':subscribetime' => $subscribetime
            );

            $sql = 'UPDATE mumm_music_user
                    set subscribe = :subscribe,
                     nickname =:nickname,
                     sex = :sex,
                     language = :language,
                     city = :city,
                     province = :province,
                     country = :country,
                     headimgurl = :headimgurl,
                     subscribetime = :subscribetime
                    WHERE openid = :openid';
            $q = $this->getConn()->prepare($sql);

            if($q->execute($mumm_music_user)) {
                return 1;
            } else {
                return 0;
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }

    public function findByOpenId($openid){
        try {
            $mumm_music_user = array(
                ':openid' => $openid);

            $sql = 'SELECT id, openid, nickname, headimgurl
                    FROM mumm_music_user
                    WHERE openid  = :openid';

            $q = $this->getConn()->prepare($sql);
            $q->execute($mumm_music_user);
            $q->setFetchMode(PDO::FETCH_ASSOC);
            while ($r = $q->fetchObject()) {
                return $r;
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }
}


// db setup test
// $obj = new UserPDO();

// TEST

// insert
// try {
//     if($c =$obj->insertSingleRow('oxqECj7JTrpVG7BfJnNCUpQap012')) {
//         echo 'A new task has been added successfully.<br>'.json_encode($c);
//     } else {
//         echo 'Error adding the task';
//     }
//     if($obj->insertSingleRow('oxqECj13-L8dz--q6dd9Z34ouTfc')) {
//         echo 'A new task has been added successfully.<br>';
//     } else {
//         echo 'Error adding the task';
//     }
// } catch (Exception $ex) {
//        echo($ex->getMessage());
// }

// try {
//     if($c =$obj->insertFullSingleRow('oxqECj30EP9zn1otAgAK0jeDkvfY', '1', 'index', '1', 'zh_CN', '徐汇', '上海', '中国',
//         'http://wx.qlogo.cn/mmopen/nuhicMSstfCxLnYMLcW1Ric0XprAI106rROoBfic31iat1VNB44Xudz3uhiafE28vyTWd1fic6ZqJf2vmCqBcyUnrwPL865h7dZcNB/0',
//         '1406632812')) {
//         echo 'A new task has been added successfully.<br>'.json_encode($c);
//     } else {
//         echo 'Error adding the task';
//     }
// } catch (Exception $ex) {
//        echo($ex->getMessage());
// }

//find
//try {
//    if($c = $obj->findByOpenId('oxqECj7JTrpVG7BfJnNCUpQap012')) {
//        echo 'Found as expected.<br>'.json_encode($c).'<br>';
//    } else {
//        echo 'Error and should found.<br>';
//    }
//} catch (Exception $ex) {
//       echo($ex->getMessage());
//}
//
//try {
//    if($c = $obj->findByOpenId('oxqECj7JTrpVG7BfJnNCUpQap0Xc')) {
//        echo 'Error and should not found.<br>'.json_encode($c);
//    } else {
//        echo 'Not found as expected.<br>';
//    }
//} catch (Exception $ex) {
//    echo($ex->getMessage());
//}