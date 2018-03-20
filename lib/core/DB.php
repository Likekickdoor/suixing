<?php
    //数据库类，封装了一些数据库函数
    namespace lib\core;
    class DB{

        public $rowcount;
        public $con;
        public $config;
        //数据库链接
        public function connect($config){
            $this->config = $config;
            $this->rowcount = 0;
            $type = $config['dbtype'];
            $host = $config['dbhost'];
            $user = $config['dbuser'];
            $dbpwd = $config['dbpwd'];
            $db_name = $config['dbname'];
            $db_charset = $config['dbcharset'];
            $this->rowcount = 0;
            $dsn = "$type:host=$host;dbname=$db_name";
            try {
                //建立持久化的PDO连接
                $this->con = new \PDO($dsn, $user, $dbpwd, array(\PDO::ATTR_PERSISTENT => true));
                $this->con->exec("set character set '$db_charset'");//读库
                $this->con->exec("set names '$db_charset'");//写库    
            } catch (Exception $e) {
                die('连接数据库失败!');
            }
        }
        //执行数据库命令的函数
        public function query($sql){
            $result = $this->con->query($sql);
            if(!$result)
                die('数据库语句执行失败');
            $this->rowcount = $result->rowCount();
            return $result;
        }
        //数据库查找函数，查找一条
        public function find($table,$condition){
            $sql = "select * from $table where $condition";
            try{
                $result = $this->con->query($sql);
                if(!$result)
                    die('数据库查询语句执行失败');
                $this->rowcount = $result->rowCount();
            }catch(Exception $e){
                die('查找数据失败');
            }
            return $result->fetch();
        }
        //查找所有
        public function findAll($table,$condition){
            $sql = "select * from $table where $condition";
            try{
                $result = $this->con->query($sql);
                if(!$result)
                    die('数据库查询语句执行失败');
                $this->rowcount = $result->rowCount();
                while($row = $result->fetch()){
                    $list[] = $row;
                }
            }catch(Exception $e){
                die('查找数据失败');
            }
            return isset($list) ? $list : "";
        }
        //插入
        public function insert($table,$data_name,$data){
            $sql = "insert into $table($data_name) values($data)";
            try{
                $result = $this->con->exec($sql);
                if(!$result)
                    die('数据库插入语句执行失败');
                $this->rowcount = 1;
            }catch(Exception $e){
                die('插入数据失败');
            }
        }
        //更新
        public function update($table,$data,$condition){
            $sql = "updata $table set $data where $condition";
            try{
                $this->rowcount = $this->con->exec($sql);
                if(!$this->rowcount)
                    die('数据库更新语句执行失败');
            }catch(Exception $e){
                die('更新数据失败');
            }
        }

    }
?>