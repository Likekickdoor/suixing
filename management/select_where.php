<?php
/**
 * 显示不同地方的查找城市最多的记录
 */
//require_once("../MVC_frame/mysql.class.php");
class select_where {
    /**
     * 查找不同地方的查找信息不一样
     */
    private $pdosqlObject;
    /**
     * 构造方法，建立对象
     */
    public function __construct(){
        $this->pdosqlObject = new pdoSql();
    }

    public function select_like_where(){
        $allresult = array();

        $sql ="SELECT city as b,COUNT(id) as num from management_userdata group by b ORDER BY num DESC LIMIT 10";
        $S_result = $this->pdosqlObject->select_sql($sql);

        if(!empty($S_result)){
            foreach($S_result as $value){
                $result = array();

                if(empty($value['b'])){
                    continue;
                }
                $sql_ip_id = "SELECT id from management_userdata where city='".$value['b']."'";
                $r_ip_id = $this->pdosqlObject->select_sql($sql_ip_id);

                $like_where = "";
                for($i=0;$i<count($r_ip_id);$i++){

                    if($i==(count($r_ip_id)-1)){
                        $like_where .= " ip_id=".$r_ip_id[$i]['id'];
                    }else{
                    $like_where .= " ip_id=".$r_ip_id[$i]['id']." or";
                    }
                }
                //var_dump($like_where);die;

                $sql_where_like = "SELECT start_city,toCity,concat(start_city,toCity) as likeCity,COUNT(id) as num from management_selectcount WHERE".$like_where." group by likeCity ORDER BY num DESC";
            //var_dump($sql_where_like);
                $r_where_like = $this->pdosqlObject->select_sql($sql_where_like);
                $result = $r_where_like[0];
                $result['city'] = $value['b'];
                
                array_push($allresult,$result);
            }
            //var_dump($allresult);
            return $allresult;
        }
    }
}
// $test = new select_where();
// $test->select_like_where();