<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 14-10-28
 * Time: 下午11:46
 */

namespace com\beyond\mumm\music\meida\dao;
require_once(dirname(__FILE__) ."/../database/DbConfig.php");

use \PDO;
use \PDOException;
use com\beyond\mumm\music\config\DbConfig;

class AudioPDO {
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

    public function insertFullSingleRow($openid, $name, $url, $macip){
        try {
            $mumm_music_audio = array(
                ':openid' => $openid,
                ':name' => $name,
                ':url' => $url,
                ':macip' => $macip
            );

            $sql = 'INSERT INTO mumm_music_audio(openid, name, url, macip)
                    VALUES(:openid, :name, :url, :macip)';
            $q = $this->getConn()->prepare($sql);

            if($q->execute($mumm_music_audio)) {
                return $this->getConn()->lastInsertId();
            } else {
                return 0;
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }

    public function findByOpenId($openid){
        try {
            $mumm_music_audio = array(
                ':openid' => $openid);

            $sql = 'SELECT openid, name, url, macip
                    FROM mumm_music_audio
                    WHERE openid  = :openid';

            $q = $this->getConn()->prepare($sql);
            $q->execute($mumm_music_audio);
            $q->setFetchMode(PDO::FETCH_ASSOC);
            while ($r = $q->fetchObject()) {
                return $r;
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }

    public function updateAudio($openid, $name, $url, $macip){
        try {
            $mumm_music_audio = array(
                ':openid' => $openid,
                ':name' => $name,
                ':url' => $url,
                ':macip' => $macip,
                ':lastupdatetime' => date("Y-m-d H:i:s", time())
            );

            $sql = 'UPDATE mumm_music_audio
                    set name =:name,
                     url = :url,
                     macip = :macip,
                     lastupdatetime = :lastupdatetime
                    WHERE openid = :openid';
            $q = $this->getConn()->prepare($sql);

            if($q->execute($mumm_music_audio)) {
                return 1;
            } else {
                return 0;
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }

} 