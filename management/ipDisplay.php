<?php
/**
 * 把最近访客的ip信息整理显示出来 主要从数据库里面进行查找
 * 
 * @author rwb
 * @version 1.0
 * @data 2018-4-6
 */
$cur_dir = dirname(__FILE__); //获取当前文件的目录
chdir($cur_dir); //把当前的目录改变为指定的目录。
require_once("../MVC_frame/mysql.class.php");


/**
 * 查找最近10条访客ip信息的类
 * @author rwb
 * @version 1.0
 * @date 2018,3.23
 */
class ipDisplay {
    private $pdosqlObject;
    /**
     * 构造方法，建立对象
     */
    public function __construct(){
        $this->pdosqlObject = new pdoSql();
    }

    /**
     * 
     * 查找最近访客的ip信息
     * @access public
     * @since  数据库封装好的类，和获取信息得类
     * @return array 这个ip信息在数据库的主键 把状态信息输出来
     */
    public function getipMessage(){
        $sql = "SELECT * FROM management_userdata ORDER BY id DESC LIMIT 10";       //查找最近新增10条用户ip信息
        
        $S_result = $this->pdosqlObject->select_sql($sql);
        if(!empty($S_result)){
            foreach($S_result as $key=>$value){
                $sql_count = "SELECT COUNT(*) FROM management_num WHERE ip_id=".$value['id'];       //查找总访问次数
                $sql_weekNum = "SELECT COUNT(*) from management_num where DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(createtime) and ip_id=".$value['id']; //查找在最近一周插进去的数据
                
                $S_resultNum = $this->pdosqlObject->select_sql($sql_count);
                $S_weekNum = $this->pdosqlObject->select_sql($sql_weekNum);
                
                $S_result[$key]['num'] = $S_resultNum[0]['COUNT(*)'];
                $S_result[$key]['weekNum'] = $S_weekNum[0]['COUNT(*)'];
            }
           
            
        }
        return $S_result;                                       //返回数据

        
    }

    /**
     * 查找浏览次数最近最多的10条用户ip信息
     * @access public
     * @since  数据库封装好的类，和获取信息得类
     * @return array 这个ip信息在数据库的主键 把状态信息输出来
     */
    public function getipMessage_num(){
        $ip_numMessage = array();
        $sql = "SELECT ip_id,count(id) as num from management_num group by ip_id order by num DESC limit 10";           //查找访问量最多的ip信息

        $S_result = $this->pdosqlObject->select_sql($sql);
        if(!empty($S_result)){
            foreach($S_result as $value){
                $sql_count = "SELECT * FROM management_userdata WHERE id=".$value['ip_id'];

                $sql_weekNum = "SELECT COUNT(*) from management_num where DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(createtime) and ip_id=".$value['ip_id']; //查找在最近一周插进去的数据

                $r_ipMessage = $this->pdosqlObject->select_sql($sql_count);
                $S_weekNum = $this->pdosqlObject->select_sql($sql_weekNum);
                $r_ipMessage[0]['num'] = $value['num'];                                             //组合总访问次数
                $r_ipMessage[0]['weekNum'] = $S_weekNum[0]['COUNT(*)'];                             //周访问次数
                array_push($ip_numMessage,$r_ipMessage[0]);                                          //组合返回数据
            }
        }

        return $ip_numMessage;
    }
}
$test = new ipDisplay();
$test->getipMessage_num();