<?php

namespace app\Model;
use \lib\core\DB;
error_reporting(0);
/**
*船务信息
*/
class ship
{
	private $start;
	private $end;
	private $line;
	private $ship_name;
	private $money;
	private $time;
	private $days;
	private $qite;
  private $aa;

//判断数据为城市还是渡口或港口
    function pan($start,$end){
    $this->start = $start;
    $this->end = $end;
    // $this->dbconfig = DB::$config;
    if(empty($start)||empty($end)){
      throw new Exception("请传入参数！");
    }
    
  	$sql = "name like '{$start}'";
  	$city_id_starts = self::findAll('id','station',$sql);
  	$city_id_start = @array_column($city_id_starts,'id','');
  	$sql = "name like '%%'";
  	$city_ids= self::findAll('city_id','ferry_id',$sql);
  	$city_id = array_column($city_ids,'city_id','');
  	
  	$sql = "name like '{$end}'";
  	$city_id_ends = self::findAll('id','station',$sql);
  	$city_id_end = @array_column($city_id_ends,'id','');
  	$diff_end = @array_intersect($city_id_end,$city_id);
  	$diff_start = @array_intersect($city_id_start,$city_id);
    @$diff_start_char = $diff_start[0];
    @$diff_end_char = $diff_end[0];

  	if($diff_start){
  		// echo $start."是城市";
      // $arr  = self::city_find($diff_start_char);
      // echo "<br/>";
      // print_r($arr);
  	}
  	else{
  		// echo $start."不是城市";
      // echo "<br/>";
  	}
    // self::ferry_city('重庆','吴淞');
    // echo "<br/>";
  	if($diff_end){
  		// echo $end."是城市";
      // echo "<br/>";
  	}
  	else{
  		// echo $end."不是城市";
      // echo "<br/>";
  	}
    // echo "<br/>";
    // self::ferry_city('朝天门',$diff_end_char);

    if($diff_start && $diff_end){
      $aa = self::city_two($diff_start_char,$diff_end_char);
      // echo "<br/>";
      // echo "两个都是城市";
      return $aa;
    }
    else if(!$diff_start && $diff_end){
      $aa = self::ferry_city($start,$diff_end_char);
      // echo "<br/>";
      // echo "起点不是城市，终点是城市";
      return $aa;
    }
    else if($diff_start && !$diff_end){
      $aa = self::city_ferry($diff_start_char,$end); 
      // echo "起点是城市，终点不是城市";
      return $aa;
    }
    else if(!$diff_start && !$diff_end){
      $aa = self::ferry_two($start,$end);
      // echo "<br/>";
      // echo "起点和终点都不是城市";
      return $aa;
    }


  }
  // self::ferry_city('吴淞','重庆');
  // $this->pan("吴淞","三岔港渡口");



//$start与$end都是城市
  public static function city_two($city1_id,$city2_id){
    $conn1 = self::city_find($city1_id);
    $conn2 = self::city_find($city2_id);

    foreach ($conn1 as $key => $value) {
      $sql = "start = '{$value}'";
      $shipmessage = self::findAll('*','details',$sql);
      // $shipmessage_arr = array_column($shipmessage);
      // print_r($shipmessage);
      if($shipmessage){
      foreach ($shipmessage as $value1) {
        $shipmessage_arr  = $value1;
        $shipmessage_arrs = array($value1["start"],
          $value1['end'],
          $value1['line'],
          $value1['days'],
          $value1['time'],
          $value1['money'],
          $value1['qite'],
          $value1['ship_name']);

            foreach ($conn2 as $value2) {
              if($value1['end']===$value2){
                // echo "<br/>";
                // echo "有航线，可以到达";
                // print_r($shipmessage_arrs);
                // echo "<br/>";
                if(empty($shipmessage_arrs[0])&&empty($shipmessage_arrs[1])&&empty($shipmessage_arrs[2])&&empty($shipmessage_arrs[3])&&empty($shipmessage_arrs[5])&&empty($shipmessage_arrs[6])&&empty($shipmessage_arrs[7])){
                  continue;
                }else{
                  $shipmessage_arrss[] = $shipmessage_arrs;
                }
                 
              }else{
                // echo "<br/>";
                // echo "无航线信息";
                continue;
              }
          }
        }
       }else{
        continue;
       }
     }
     // print_r($shipmessage_arrss);
     return  $shipmessage_arrss;
  }



//$start与$end都是渡口或港口
  public static function ferry_two($start,$end){
    $sql = "start = '{$start}' AND end = '{$end}'";
    $shipmessage = self::findAll('*','details',$sql);
    foreach ($shipmessage as $value) {
      // print_r($value);
      // echo "<br/>";
       $shipmessage_arrss[] = array($value["start"],
          $value['end'],
          $value['line'],
          $value['days'],
          $value['time'],
          $value['money'],
          $value['qite'],
          $value['ship_name']);
    }
    // print_r($shipmessage_arrss);
    return  $shipmessage_arrss; 
  }




//$start是城市，而$end是渡口或港口
  public static function city_ferry($start,$end){
    $conn1 = self::city_find($start);
    foreach ($conn1 as $key => $value) {
      $sql = "start = '{$value}'";
      $shipmessage = self::findAll('*','details',$sql);
      // $shipmessage_arr = array_column($shipmessage);
      if($shipmessage){
      foreach ($shipmessage as $value1) {
        $shipmessage_arr  = $value1;
        $shipmessage_arrs = array($value1["start"],
          $value1['end'],
          $value1['line'],
          $value1['days'],
          $value1['time'],
          $value1['money'],
          $value1['qite'],
          $value1['ship_name']);

              if($value1['end']===$end){
                // echo "<br/>";
                // echo "有航线，可以到达";
                // print_r($shipmessage_arrs);
                $shipmessage_arrss[] = $shipmessage_arrs;
              }
          
        }
       }else{
        continue;
       }
     }
     // print_r($shipmessage_arrss);
     if($shipmessage_arrss){
      return $shipmessage_arrss;
    }else{
      echo " ";
    } 
  }



//$start是渡口或港口，$end是城市
  public static function  ferry_city($start,$end){
    $conn1 = self::city_find($end);
    foreach ($conn1 as $key => $value) {
      $sql = "end = '{$value}'";
      $shipmessage = self::findAll('*','details',$sql);
      // $shipmessage_arr = array_column($shipmessage);
      if($shipmessage){
      foreach ($shipmessage as $value1) {
        $shipmessage_arr  = $value1;
        $shipmessage_arrs = array($value1["start"],
          $value1['end'],
          $value1['line'],
          $value1['days'],
          $value1['time'],
          $value1['money'],
          $value1['qite'],
          $value1['ship_name']);

              if($value1['start']===$start){
                // echo "<br/>";
                // echo "有航线，可以到达";
                // print_r($shipmessage_arrs);
                $shipmessage_arrss[]  = $shipmessage_arrs;
              }
          
        }
       }else{
        continue;
       }
       // print_r($shipmessage_arrss);
       return $shipmessage_arrss;
     }

  }





//通过城市来查询渡口或者港口
  public static function city_find($content){
    $id = $content;
    $sql = "city_id = '{$content}'";
    $ferry = self::findAll('name','ferry_id',$sql);
    $ferry_arr = array_column($ferry,'name','');
    return $ferry_arr;
  }




//查询所有
  public static function findAll($field,$table,$condition){
            $sql = "select $field from $table where $condition";
            try{
                $result = DB::$con->query($sql);
                if(!$result)
                    die('数据库查询语句执行失败');
                DB::$rowcount = $result->rowCount();
                while($row = $result->fetch()){
                    $list[] = $row;
                }
            }catch(Exception $e){
                die('查找数据失败');
            }
            return isset($list) ? $list : "";
        }
}

// $ship = new ship();
// $ship->pan();
?>