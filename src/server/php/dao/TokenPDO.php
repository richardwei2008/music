<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 14-10-28
 * Time: 下午11:46
 */

namespace com\beyond\mumm\music\security\dao;
require_once(dirname(__FILE__) ."/../database/DbConfig.php");

use \PDO;
use \PDOException;
use com\beyond\mumm\music\config\DbConfig;

class TokenPDO {
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
            $mumm_music_sec = array(
                ':openid' => $openid);

            $sql = 'INSERT INTO mumm_music_sec(openid)
                    VALUES(:openid)';
            $q = $this->getConn()->prepare($sql);

            if($q->execute($mumm_music_sec)) {
                return $this->getConn()->lastInsertId();
            } else {
                return 0;
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }

    public function insertFullSingleRow($openid, $accesstoken, $token, $nonce, $servertime, $sessionid, $macip){
        try {
            $mumm_music_sec = array(
                ':openid' => $openid,
                ':wid' => md5($openid),
                ':accesstoken' => $accesstoken,
                ':token' => $token,
                ':nonce' => $nonce,
                ':servertime' => $servertime,
                ':sessionid' => $sessionid,
                ':macip' => $macip
            );

            $sql = 'INSERT INTO mumm_music_sec(openid, wid, accesstoken, token, nonce, servertime, sessionid, macip)
                    VALUES(:openid, :wid, :accesstoken, :token, :nonce, :servertime, :sessionid, :macip)';
            $q = $this->getConn()->prepare($sql);

            if($q->execute($mumm_music_sec)) {
                return $this->getConn()->lastInsertId();
            } else {
                return 0;
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }

    public function findByAccessToken($accesstoken){
        try {
            $mumm_music_sec = array(
                ':accesstoken' => $accesstoken);

            $sql = 'SELECT openid, wid, accesstoken, token, nonce, sessionid, macip
                    FROM mumm_music_sec
                    WHERE accesstoken  = :accesstoken';

            $q = $this->getConn()->prepare($sql);
            $q->execute($mumm_music_sec);
            $q->setFetchMode(PDO::FETCH_ASSOC);
            while ($r = $q->fetchObject()) {
                return $r;
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }

    public function findByOpenid($openid){
        try {
            $mumm_music_sec = array(
                ':openid' => $openid);

            $sql = 'SELECT openid, wid, accesstoken, token, nonce, sessionid, macip
                    FROM mumm_music_sec
                    WHERE accesstoken  = :accesstoken';

            $q = $this->getConn()->prepare($sql);
            $q->execute($mumm_music_sec);
            $q->setFetchMode(PDO::FETCH_ASSOC);
            while ($r = $q->fetchObject()) {
                return $r;
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }

    public function findByWid($wid){
        try {
            $mumm_music_sec = array(
                ':wid' => $wid);

            $sql = 'SELECT openid, wid, accesstoken, token, nonce, sessionid, macip
                    FROM mumm_music_sec
                    WHERE accesstoken  = :accesstoken';

            $q = $this->getConn()->prepare($sql);
            $q->execute($mumm_music_sec);
            $q->setFetchMode(PDO::FETCH_ASSOC);
            while ($r = $q->fetchObject()) {
                return $r;
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }

    public function checkIp($macip, $dateFrom, $dateTo){
        try {
            $mumm_music_sec = array(
                ':macip' => $macip,
                ':dateFrom'     => $dateFrom,
                ':dateTo'     => $dateTo
            );

            $sql = 'SELECT count(1) as numberOfWinner
                    FROM mumm_music_sec
                    WHERE macip = :macip
                    AND createtime BETWEEN :dateFrom AND :dateTo';

            $q = $this->getConn()->prepare($sql);
            $q->execute($mumm_music_sec);
            $q->setFetchMode(PDO::FETCH_ASSOC);
            while ($r = $q->fetchObject()) {
                return $r;
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }
} 