<?php
    namespace lib\core;
    class DB{

        public static $db;
        public static $config;
        public static $rowcount;

        public static function init($config){
            $sql = 'lib\db\\'.$config['dbtype'];
            self::$db = new $sql();
            self::$config = $config;
            self::$db->connect($config);
            self::$rowcount = 0;
        }

        public static function query($sql){
            $result = self::$db->query($sql);
            self::$rowcount = self::$db->rowcount;
            return $result;
        }

        public static function find($table,$condition){
            $result = self::$db->find($table,$condition);
            self::$rowcount = self::$db->rowcount;
            return $result;
        }

        public static function findAll($table,$condition){
            $result = self::$db->findAll($table,$condition);
            self::$rowcount = self::$db->rowcount;
            return $result;
        }

        public static function insert($table,$data_name,$data){
            $result = self::$db->insert($table,$data_name,$data);
            self::$rowcount = self::$db->rowcount;
            return $result;
        }

        public static function update($table,$data,$condition){
            $result = self::$db->update($table,$data,$condition);
            self::$rowcount = self::$db->rowcount;
            return $result;
        }
    }
?>