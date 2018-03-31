<?php
$pdo=new PDO('mysql:host=localhost;dbname=trains','root','');
$pdo->query('set names utf8');
/**
* 取出来停站点和站站距离
*/
class StopInfo
{
	
	function info($pdo,$about_id)
	{
		$sql='SELECT `stationSort`,`stationName`,`distance` FROM `station_stop` WHERE `about_id`='.$about_id.' AND `status`=1 ORDER BY `stationSort`';
		$result = $pdo->query($sql);
		$StopInfo=$result->fetchAll();//PDO::FETCH_ASSOC
		return $StopInfo;
	}

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
				$StaDistance.='<div>'.$distancex.'</div> ';
			}
		}
		return $StaDistance;
	}
}


if(isset($_REQUEST)){
	$about_id=$_REQUEST['about_id'];//前端传来
	$dpSort=$_REQUEST['dpSort'];//前端传来
	$arrSort=$_REQUEST['arrSort'];//前端传来
	$Info=new StopInfo;
	$StopInfo=$Info->info($pdo,$about_id);
	$StaPoint=$Info->StaPoint($StopInfo,$dpSort,$arrSort);
	// print_r($StaPoint);
	$StaDistance=$Info->StaDistance($StopInfo,$dpSort,$arrSort);
	// print_r($StaDistance);
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
	    	</div>
	    	<div class="space"></div>
	        <div class="saveStationsName">
	        <div>信息丢失</div>
	        </div>
str;
}


?>
	    