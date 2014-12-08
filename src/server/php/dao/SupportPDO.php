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


class SupportPDO {
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

    public function insertSingleRow($openid, $support_openid, $macip){
        try {
            $mumm_music_support = array(
                ':openid' => $openid,
                ':support_openid' => $support_openid,
                ':macip' => $macip);

            $sql = 'INSERT INTO mumm_music_support(openid, support_openid, macip)
                    VALUES(:openid, :support_openid, :macip)';
            $q = $this->getConn()->prepare($sql);
            return $q->execute($mumm_music_support);
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }

    }
    public function findNumberOfSupport($openid){
        try {
            $mumm_music_support = array(
                ':openid' => $openid
            );
            $sql = 'SELECT count(1) as numberOfSupport
                    FROM mumm_music_support
                    WHERE openid  = :openid';

            $q = $this->getConn()->prepare($sql);
            $q->execute($mumm_music_support);
            $q->setFetchMode(PDO::FETCH_ASSOC);
            while ($r = $q->fetchObject()) {
                return $r;
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }

    public function findNumberOfSupportBetween($openid, $dateFrom, $dateTo){
        try {
            $mumm_music_support = array(
                ':openid'       => $openid,
                ':dateFrom'     => $dateFrom,
                ':dateTo'     => $dateTo
            );
            $sql = 'SELECT count(1) as numberOfSupport
                    FROM mumm_music_support
                    WHERE openid  = :openid
                    AND createtime BETWEEN :dateFrom AND :dateTo';

            $q = $this->getConn()->prepare($sql);
            $q->execute($mumm_music_support);
            $q->setFetchMode(PDO::FETCH_ASSOC);
            while ($r = $q->fetchObject()) {
                return $r;
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }

    public function findNumberOfSupportBetweenBySupportOpenid($openid, $support_openid, $dateFrom, $dateTo){
        try {
            $mumm_music_support = array(
                ':openid'       => $openid,
                ':support_openid'       => $support_openid,
                ':dateFrom'     => $dateFrom,
                ':dateTo'     => $dateTo
            );
            $sql = 'SELECT count(1) as numberOfSupport
                    FROM mumm_music_support
                    WHERE openid  = :openid
                    AND support_openid  = :support_openid
                    AND createtime BETWEEN :dateFrom AND :dateTo';

            $q = $this->getConn()->prepare($sql);
            $q->execute($mumm_music_support);
            $q->setFetchMode(PDO::FETCH_ASSOC);
            while ($r = $q->fetchObject()) {
                return $r;
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }

    public function findNumberOfSupportBetweenBySupportIp($openid, $macip, $dateFrom, $dateTo){
        try {
            $mumm_music_support = array(
                ':openid'       => $openid,
                ':macip'       => $macip,
                ':dateFrom'     => $dateFrom,
                ':dateTo'     => $dateTo
            );
            $sql = 'SELECT count(1) as numberOfSupport
                    FROM mumm_music_support
                    WHERE openid  = :openid
                    AND macip  = :macip
                    AND createtime BETWEEN :dateFrom AND :dateTo';

            $q = $this->getConn()->prepare($sql);
            $q->execute($mumm_music_support);
            $q->setFetchMode(PDO::FETCH_ASSOC);
            while ($r = $q->fetchObject()) {
                return $r;
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }

    public function findTopOfSupportBetween($dateFrom, $dateTo){
        try {
            $mumm_music_support = array(
                ':dateFrom'     => $dateFrom,
                ':dateTo'     => $dateTo
            );
            $sql = "SELECT s.openid, t.url, i.nickname, count(1) as numberOfSupport
                    FROM mumm_music_support s LEFT JOIN mumm_music_image t on (t.openid = s.openid) LEFT JOIN mumm_music_user i on (i.openid = s.openid)
                    WHERE s.createtime BETWEEN :dateFrom AND :dateTo
                    GROUP BY s.openid, t.url, t.openid
                    ORDER BY numberOfSupport DESC
                    LIMIT 10";

            $q = $this->getConn()->prepare($sql);
            $q->execute($mumm_music_support);
            $q->setFetchMode(PDO::FETCH_ASSOC);
            $r = $q->fetchAll();
            return $r;
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }


    public function findMore(){
        try {
            $mumm_music_support = array(
                ':dateFrom'     => null,
                ':dateTo'     => null
            );
            $sql = "SELECT a.openid, MIN( t.url ) as url
FROM mumm_music_audio a
LEFT JOIN mumm_music_image t ON ( t.openid = a.openid )
WHERE a.openid IS NOT NULL
AND a.openid <>  'null'
AND t.invalid =0
AND t.url IS NOT NULL
AND t.url <>  'null'
GROUP BY a.openid
ORDER BY a.createtime DESC
LIMIT 0 , 16";

            $q = $this->getConn()->prepare($sql);
            $q->execute($mumm_music_support);
            $q->setFetchMode(PDO::FETCH_ASSOC);
            $r = $q->fetchAll();
            return $r;
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }
}


//// db setup test
// $obj = new SupportPDO();
//
// try {
//     if($obj->insertSingleRow('oxqECj7JTrpVG7BfJnNCUpQap012', 'oHTD_tpKyYb1rZsDEhNe3dL-Evhg', '192.168.10.1')) {
//         echo 'A new task has been added successfully<br>';
//     } else {
//         echo 'Error adding the task<br>';
//     }
// } catch (Exception $ex) {
//        echo($ex->getMessage());
// }
//
//try {
//    if($obj->insertSingleRow('oxqECj7JTrpVG7BfJnNCUpQap012', 'oHTD_tvXZwJs-rfQGKe_tz0bMro8', '192.168.10.2')) {
//        echo 'A new task has been added successfully<br>';
//    } else {
//        echo 'Error adding the task<br>';
//    }
//} catch (Exception $ex) {
//    echo($ex->getMessage());
//}
//
//try {
//    if($numberOfSupport = $obj->findNumberOfSupport('oxqECj7JTrpVG7BfJnNCUpQap012')) {
//        echo '<br>Found.<br>'.(var_dump($numberOfSupport));
//    } else {
//        echo '<br>Error.<br>';
//    }
//} catch (Exception $ex) {
//    echo($ex->getMessage());
//}
//
//try {
//    if($numberOfSupport = $obj->findNumberOfSupportBetween('oxqECj7JTrpVG7BfJnNCUpQap012', '2014-10-28', '2014-10-29')) {
//        echo '<br>Found.<br>'.(var_dump($numberOfSupport));
//    } else {
//        echo '<br>Error.<br>';
//    }
//} catch (Exception $ex) {
//    echo($ex->getMessage());
//}
//
//try {
//    if($numberOfSupport = $obj->findNumberOfSupportBetween('oxqECj7JTrpVG7BfJnNCUpQap012', '2014-10-28 23:00:00', '2014-10-28 23:50:00')) {
//        echo '<br>Found.<br>'.(var_dump($numberOfSupport));
//    } else {
//        echo '<br>Error.<br>';
//    }
//} catch (Exception $ex) {
//    echo($ex->getMessage());
//}

//
//try {
//    if($topOfSupport = $obj->findTopOfSupportBetween('2014-09-22', '2014-09-23')) {
//        echo '<br>Found.<br>'.(var_dump($topOfSupport));
//    } else {
//        echo '<br>Error.<br>';
//    }
//} catch (Exception $ex) {
//    echo($ex->getMessage());
//}
//