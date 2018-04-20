<?php
namespace app\Model;
use lib\core\DB;

// $pdo=new PDO('mysql:host=localhost;dbname=trains','root','');
// $pdo->query('set names utf8');
/**
* 取出来停站点和站站距离
*/
class StopInfo
{
	
	function info($pdo,$about_id)
	{
		$sql='SELECT DISTINCT `stationSort`,`stationName`,`distance` FROM `station_stop` WHERE `about_id`='.$about_id.' AND `status`=1 ORDER BY `stationSort`';
		$result = $pdo->query($sql);
		$StopInfo=$result->fetchAll();//PDO::FETCH_ASSOC
		return $StopInfo;
	}

	//**Dots样式显示**//
	function StaPoint($StopInfo,$dpSort,$arrSort){
		$StaPoint='';
		foreach ($StopInfo as $key => $value)
		{
			if($key>($dpSort-1)&&$key<($arrSort-1))
			{
				$StaPoint.='<div>'.$value['stationName'].'</div>';
			}
		}
		return $StaPoint;
	}

	function StaDistance($StopInfo,$dpSort,$arrSort){
		$StaDistance='';
		foreach ($StopInfo as $key => $value)
		{
			if($key>=($dpSort-1)&&$key<($arrSort-1))
			{	
				$distancex=$StopInfo[$key+1]['distance']-$StopInfo[$key]['distance'];
				$StaDistance.='<div>'.abs($distancex).'</div> ';
			}
		}
		return $StaDistance;
	}

	function get_stop_info($about_id,$dpSort,$arrSort){
		// if(isset($about_id)&&isset($dpSort)&&isset($arrSort)){
		// isset($_REQUEST)
		// $about_id=$_REQUEST['about_id'];//前端传来
		// $dpSort=$_REQUEST['dpSort'];//前端传来
		// $arrSort=$_REQUEST['arrSort'];//前端传来
		$Info=new StopInfo;
		$pdo=DB::$con;
		$StopInfo=$Info->info($pdo,$about_id);
		if(!empty($StopInfo)){

		$StaPoint=$Info->StaPoint($StopInfo,$dpSort,$arrSort);		
		$StaDistance=$Info->StaDistance($StopInfo,$dpSort,$arrSort);
		
		echo $retstr=<<<str
					<div class="saveStationsGap">
			        {$StaDistance}
			    	</div>
			    	<div class="space"></div>
			        <div class="saveStationsName">
			        {$StaPoint}
			        </div>
str;
		}else{
		echo $retstr=<<<str
					<div class="saveStationsGap">
			        <div>0</div>
			        <div>0</div>
			    	</div>
			    	<div class="space"></div>
			        <div class="saveStationsName">
			        <div>暂无信息</div>
			        <div>暂无信息</div>
			        </div>
str;
		}	
	}

	//**Windows样式显示**//
	function StaPointWin($StopInfo,$dpSort,$arrSort){
          $StaPoint=[];
          foreach ($StopInfo as $key => $value)
          {
               if($key>($dpSort-1)&&$key<($arrSort-1))
               {
                    array_push($StaPoint,$value['stationName']);
               }
          }
          return $StaPoint;
     }

    function StaDistanceWin($StopInfo,$dpSort,$arrSort){
          $StaDistance=[];
          foreach ($StopInfo as $key => $value)
          {
               if($key>=($dpSort-1)&&$key<($arrSort-2))
               {    
                    $distancex=$StopInfo[$key+1]['distance']-$StopInfo[$key]['distance'];
                    array_push($StaDistance,abs($distancex));
               }
          }
          return $StaDistance;
     }

    function get_stop_infoWin($about_id,$dpSort,$arrSort){
          
          $Info=new StopInfo;
          $pdo=DB::$con;//句柄
          $StopInfo=$Info->info($pdo,$about_id);
          if(!empty($StopInfo)){

          $StaPoint=$Info->StaPointWin($StopInfo,$dpSort,$arrSort);      
          $StaDistance=$Info->StaDistanceWin($StopInfo,$dpSort,$arrSort);
               $str='';
               for($key=0;$key<count($StaPoint);$key++){
                    $str.="<div>{$StaPoint[$key]}<span>{$StaDistance[$key]}</span></div>";
               }
               echo $str;
               exit();
          }else{
               $str='<div>暂无数据<span>0</span></div>';
               echo $str;
               exit();
          }    
     }

}



?>