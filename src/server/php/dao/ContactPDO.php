<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 14-10-28
 * Time: 下午11:46
 */

namespace com\beyond\mumm\music\profile\dao;
require_once(dirname(__FILE__) ."/../database/DbConfig.php");

use \PDO;
use \PDOException;
use com\beyond\mumm\music\config\DbConfig;

class ContactPDO {
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

    public function insertFullSingleRow($openid, $name, $cellphone, $macip){
        try {
            $mumm_music_contact = array(
                ':openid' => $openid,
                ':name' => $name,
                ':cellphone' => $cellphone,
                ':macip' => $macip
            );

            $sql = 'INSERT INTO mumm_music_contact(openid, name, cellphone, macip)
                    VALUES(:openid, :name, :cellphone, :macip)';
            $q = $this->getConn()->prepare($sql);

            if($q->execute($mumm_music_contact)) {
                return $this->getConn()->lastInsertId();
            } else {
                return 0;
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }

    public function insertFullSingleRow1($openid, $name, $cellphone, $macip){
        try {
            $mumm_music_contact = array(
                ':openid' => $openid,
                ':name' => $name,
                ':cellphone' => $cellphone,
                ':macip' => $macip,
                ':category' => rand(1, 3)
            );

            $sql = 'INSERT INTO mumm_music_contact(openid, name, cellphone, macip, category)
                    VALUES(:openid, :name, :cellphone, :macip, :category)';
            $q = $this->getConn()->prepare($sql);

            if($q->execute($mumm_music_contact)) {
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
            $mumm_music_contact = array(
                ':openid' => $openid);

            $sql = 'SELECT openid, name, cellphone, macip, category
                    FROM mumm_music_contact
                    WHERE openid  = :openid';

            $q = $this->getConn()->prepare($sql);
            $q->execute($mumm_music_contact);
            $q->setFetchMode(PDO::FETCH_ASSOC);
            while ($r = $q->fetchObject()) {
                return $r;
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }

    public function updateContact($openid, $name, $cellphone, $macip){
        try {
            $mumm_music_contact = array(
                ':openid' => $openid,
                ':name' => $name,
                ':cellphone' => $cellphone,
                ':macip' => $macip,
                ':lastupdatetime' => date("Y-m-d H:i:s", time())
            );

            $sql = 'UPDATE mumm_music_contact
                    set name =:name,
                     cellphone = :cellphone,
                     macip = :macip,
                     lastupdatetime = :lastupdatetime
                    WHERE openid = :openid';
            $q = $this->getConn()->prepare($sql);

            if($q->execute($mumm_music_contact)) {
                return 1;
            } else {
                return 0;
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }

    public function updateContact1($openid, $name, $cellphone, $macip){
        try {
            $mumm_music_contact = array(
                ':openid' => $openid,
                ':name' => $name,
                ':cellphone' => $cellphone,
                ':macip' => $macip,
                ':category' => rand(1, 3),
                ':lastupdatetime' => date("Y-m-d H:i:s", time())
            );

            $sql = 'UPDATE mumm_music_contact
                    set name =:name,
                     cellphone = :cellphone,
                     macip = :macip,
                     category = :category,
                     lastupdatetime = :lastupdatetime
                    WHERE openid = :openid';
            $q = $this->getConn()->prepare($sql);

            if($q->execute($mumm_music_contact)) {
                return 1;
            } else {
                return 0;
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }
} 