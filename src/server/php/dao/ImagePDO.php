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


class ImagePDO {
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

    public function insertFullSingleRow($openid, $name, $dir, $url, $macip){
        try {
            $mumm_music_image = array(
                ':openid' => $openid,
                ':name' => $name,
                ':dir' => $dir,
                ':url' => $url,
                ':macip' => $macip
            );

            $sql = 'INSERT INTO mumm_music_image(openid, name, dir, url, macip)
                    VALUES(:openid, :name, :dir, :url, :macip)';
            $q = $this->getConn()->prepare($sql);

            if($q->execute($mumm_music_image)) {
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
            $mumm_music_image = array(
                ':openid' => $openid,
                ':current_invalid' => 0);

            $sql = 'SELECT openid, name, dir, url, macip
                    FROM mumm_music_image
                    WHERE openid  = :openid
                    AND invalid = :current_invalid';

            $q = $this->getConn()->prepare($sql);
            $q->execute($mumm_music_image);
            $q->setFetchMode(PDO::FETCH_ASSOC);
            $r = $q->fetchAll();
            return $r;
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }

    public function invalidImage($openid){
        try {
            $mumm_music_image = array(
                ':openid' => $openid,
                ':current_invalid' => 0,
                ':invalid' => 1,
                ':lastupdatetime' => date("Y-m-d H:i:s", time())
            );

            $sql = 'UPDATE mumm_music_image
                    set invalid =:invalid,
                     lastupdatetime = :lastupdatetime
                    WHERE openid = :openid
                    AND invalid = :current_invalid';
            $q = $this->getConn()->prepare($sql);

            if($q->execute($mumm_music_image)) {
                return 1;
            } else {
                return 0;
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }

} 