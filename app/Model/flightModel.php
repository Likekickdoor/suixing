<?php
namespace app\Model;
use lib\core\DB;



class flight {
    private $pdoObject;
    /**
     * 构造方法，建立对象
     */
    public function __construct(){
        
       // $this->pdoObject = DB;
    }
    /**
     * 查找航班数据
     */
    public function searchFlight(){
        $allFlightMessage = array();
        
        $routeData = $this->select_line();
        if(empty($routeData)){
            echo "没有找到航线，可以查看其它线路";
        }else{
            foreach($routeData as $key=>$value){
                $s_flightTableWhere = array("f_r_id"=>$value['r_id']);
                $r_flightTable = DB::select_all("flight_table",array("*"),$s_flightTableWhere);
                //var_dump($r_flightTable);
                if(empty($r_flightTable)){
                    echo "没有找到航班信息";

                }else{
                    foreach($r_flightTable as $k=>$v){
                        
                        $flightMessage = array_merge($v,$value);
                        
                        array_push($allFlightMessage,$flightMessage);
                    }
                }
            }
        }
        //var_dump($allFlightMessage);
        //echo json_encode($allFlightMessage,JSON_UNESCAPED_UNICODE);//JSON_UNESCAPED_UNICODE让中文不编码
        return $allFlightMessage;
    }



    /**
     * 把输入的两个城市去掉市，区，县，特别行政区
     */

     public function select_city($cityName){
        $cityName = str_replace(array("市","区","县","特别行政区","州"),"",$cityName);
        $s_cityLike = "SELECT * FROM city WHERE `city` like '".$cityName."%'";
        $s_cityLike_r = DB::select_sql($s_cityLike);
        return $s_cityLike_r;
        
     }
    /**
       * 把起点城市和终点城市
       * 
       */
      public function select_line() {
         
        if(!empty($_GET['dpplace'])&&!empty($_GET['arrplace'])){
            $cityLine = array();
            $startCity = $this->select_city($_GET['dpplace']);
            $toCity = $this->select_city($_GET['arrplace']);
               
            foreach($startCity as $key=>$value){
                foreach($toCity as $k=>$v){
                    $s_route = "SELECT * FROM route_table where r_from_id=".$value['id']." AND r_to_id=".$v['id'];
                    $s_route_r = DB::select_sql($s_route);

                    if(empty($s_route_r)){

                    }else{
                        $cityLine[]=$s_route_r[0];
                    }
                }
            }
            

            //插入查询记录信息
               /* if(!empty($_SESSION['ip'])){
                   $i_seleclcont = array("ip_id"=>$_SESSION['ip'],"original_start"=>$_GET['dpplace'],
               "starCity_id"=>$startCity[0]['id'],"start_city"=>$startCity[0]['city'],"original_to"=>$_GET['arrplace'],
           "tocity_id"=>$toCity[0]['id'],"toCity"=>$toCity[0]['city']);
               }else{
                   $i_seleclcont = array("original_start"=>$_GET['dpplace'],
                   "starCity_id"=>$startCity[0]['id'],"start_city"=>$startCity[0]['city'],"original_to"=>$_GET['arrplace'],
               "tocity_id"=>$toCity[0]['id'],"toCity"=>$toCity[0]['city']);
                    $i_select = "original_start,starCity_id,start_city,original_to,tocity_id,toCity";
                    $i_data = $_GET['dpplace'].",".
               }

               DB::insert("management_selectcount",$i_seleclcont);
 */
               return  $cityLine;
       }
     }
    
}
