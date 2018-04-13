<?php
/**
 * 留言信息的查找
 */
class leaveDisplay {
    /**
     * 用户查找信息的显示
     */
    private $pdosqlObject;
    /**
     * 构造方法，建立对象
     */
    public function __construct(){
        $this->pdosqlObject = new pdoSql();
    }
    
    /**
     * 查找留言信息
     */
    public function getLeavMessage(){
        /* $sql_watch "SELECT * FROM management_leave where is_watch=0";
        $r_watch = $this->pdosqlObject->select_sql($sql_watch);
        if(!empty($r_watch)){ */
        $sql = "SELECT * FROM management_leave where is_watch=0 ORDER BY id DESC limit 5"; //查找没有查看的留言信息
        $r_levae = $this->pdosqlObject->select_sql($sql);
        /* }else{

        } */
//var_dump( $r_levae);
        if(!empty($r_levae)){
            if((int)count($r_levae)<5){
                $length = 5-(int)count($r_levae);
                $sql_isWatch = "SELECT * FROM management_leave where is_watch=1 ORDER BY id DESC limit ".$length;     //查找来满足10条
                $r_leaveIsWatch = $this->pdosqlObject->select_sql($sql_isWatch);
                if(!empty($r_leaveIsWatch)){
                    $r_levae = array_merge($r_levae,$r_leaveIsWatch);
                }
            }
            $sql_noWatch = "SELECT COUNT(id) FROM management_leave where is_watch=0";               //查找未查看总数
            $r_noWatch = $this->pdosqlObject->select_sql($sql_noWatch);

            $r_levae[0]['num'] = $r_noWatch[0]['COUNT(id)'];
        }else{
            $sql_isWatch = "SELECT * FROM management_leave ORDER BY id DESC limit 10";
            $r_levae = $this->pdosqlObject->select_sql($sql_isWatch);
            $r_levae[0]['num'] = 0;
        }
        return $r_levae;
    }
}