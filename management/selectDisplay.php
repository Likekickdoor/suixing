<?php
/**
 * 用于查找用户查找路线的数据
 */
class selectDisplay {
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

    public function getSelectMessage(){
        $sql = "SELECT 'original_start','original_to',count( * ) AS count FROM management_selectcount ORDER BY  count DESC LIMIT 10";
        
        $S_result = $this->pdosqlObject->select_sql($sql);
        var_dump("$S_result");
        if(!empty($S_result)){
            foreach($S_result as $key=>$value){
                $sql_count = "SELECT COUNT(*) FROM management_num WHERE ip_id=".$value['id'];
                $sql_weekNum = "SELECT COUNT(*) from management_num where DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(createtime) and ip_id=".$value['id']; //查找在最近一周插进去的数据
                
                $S_resultNum = $this->pdosqlObject->select_sql($sql_count);
                $S_weekNum = $this->pdosqlObject->select_sql($sql_weekNum);
                var_dump($S_weekNum);
                $S_result[$key]['num'] = $S_resultNum[0]['COUNT(*)'];
                $S_result[$key]['weekNum'] = $S_weekNum[0]['COUNT(*)'];
            }
            
        }
    }
}