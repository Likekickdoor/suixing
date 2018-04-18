<?php
// require_once('../Progrom_Train/UpdateTrains/phpQuery/phpQuery.php');
// $about_id=222;$trainNo='G66';
// $context    = file_get_contents('Onetrain.html');
// $table_obj=trainsTable114::phpqueryReturn114($context);
// $stop_table=trainsTable114::returnDeal114($table_obj,$about_id,$trainNo);
// var_dump($stop_table);
/**
* 负责收集停站表
*/
class trainsTable114
{

	function phpqueryReturn114($context){
	 	//通过phpQuery对象将源代码进行解析
	    $document   = phpQuery::newDocumentHTML($context);
	    //选取到指定的容器
	    $container  = phpQuery::pq("div.list");
	    //用来找回行数
	    $table		=$container->find("tr");//行数
	    $rows=count($table);
	    if($rows==0)
	    {
	    	return 0;
	    }
	    return $table;
    }
	
	function returnDeal114($table,$about_id,$trainNo){
		//遍历出每行内容
		$rows=count($table);
	    $stop_table=[];
	    foreach ($table as $row =>$value)
	    {
	    	if($row==0||$row==$rows-1)
	    	{
	    		continue;
	    	}
	   		$stationSort=pq($value)->find("td:eq(0)")->text();//站次
	   		$stationName=pq($value)->find("td:eq(1)")->text();//车站
	   		$arrTime=pq($value)->find("td:eq(2)")->text();//到时
	   		$arrTime=='--'?$arrTime='00:00':$arrTime;   		
	   		$dpTime=pq($value)->find("td:eq(3)")->text();//发车
	   		$distance=pq($value)->find("td:eq(4)")->text();//里程
	   		$distance=='--'?$distance=0:$distance;
	   		
	   		
	   		/*将取到的信息推到表中，随后加上花费时间信息*/
	   		$line[0]=$about_id;//将传入的车次tid 也存入每一行中
	   		$line[1]=$trainNo;//G2151
	   		$line[2]=$stationSort;
	   		$line[3]=$stationName;
	   		$line[4]=$arrTime;
	   		$line[5]=$dpTime;
	   		$line[8]=$distance;
	   		array_push($stop_table,$line);
	    }
	   	return self::AddRunTime($stop_table);
	}

	/**
	*算出"两时间点"间的时间(分钟)
	*/
	private static function abtime($startdate,$enddate){
		$dpTime=strtotime($startdate);
		$arrTime=strtotime($enddate);
		$time=date('H:i',$arrTime-$dpTime);
		$hour=mb_substr($time,0,2);
		$minute=mb_substr($time,3);
		$otm=(int)$hour*60+(int)$minute;
		return $otm;
	}
	/**
	*加上从出站到本站的时间runTime、overTime(分钟)
	*/
	private static function  AddRunTime($table){
		$temp_time=0;
		for($i=0;$i<count($table);$i++)
		{
			if($i==0){//起始站
				$table[0][6]=0;//overTime
				$table[0][7]=0;//runTime
				$temp_time=$table[0][7];//runTime
			}else{
				$big=self::abtime($table[$i-1][5],$table[$i][5]);//'dpTime''dpTime'
				$small=self::abtime($table[$i][4],$table[$i][5]);//'arrTime''dpTime'
				$table[$i][6]=$small;//'overTime'
				$table[$i][7]=$big+$temp_time;//'runTime'
				$temp_time=$table[$i][7];//'runTime'
			}

		}

		return $table;
	}

}

// try{
// 		$url='Onetrain.html';
// 		// $url='Nofind.html';
// 		// $url='http://checi.114piaowu.com/Y504';
// 		//获取页面源代码
// 		$context    = file_get_contents($url);
// 	 	//通过phpQuery对象将源代码进行解析
// 	    $document   = phpQuery::newDocumentHTML($context);
// 	    //选取到指定的容器
// 	    $container  = phpQuery::pq("div.list");
	    
// 	    //用来找回行数
// 	    $table		=$container->find("tr");//行数
// 	    $rows=count($table);
// 	    //遍历出每行内容
// 	    $stop_table=[];
// 	    foreach ($table as $row =>$value)
// 	    {
// 	    	if($row==0||$row==$rows-1)
// 	    	{
// 	    		continue;
// 	    	}
// 	   		echo $stationSort=pq($value)->find("td:eq(0)")->text();//站次
// 	   		echo $stationName=pq($value)->find("td:eq(1)")->text();//车站
// 	   		$arrTime=pq($value)->find("td:eq(2)")->text();//到时
// 	   		$arrTime=='--'?$arrTime='00:00':$arrTime;
// 	   		echo $arrTime;
	   		
// 	   		echo $dpTime=pq($value)->find("td:eq(3)")->text();//发车
// 	   		$distance=pq($value)->find("td:eq(4)")->text();//里程
// 	   		$distance=='--'?$distance=0:$distance;
// 	   		echo $distance.'<br/>';
	   		
// 	   		/*将取到的信息推到表中，随后加上花费时间信息*/
// 	   		$line[0]=$about_id;//将传入的车次tid 也存入每一行中
// 	   		$line[1]=$trainNo;//G2151
// 	   		$line[2]=$stationSort;
// 	   		$line[3]=$stationName;
// 	   		$line[4]=$arrTime;
// 	   		$line[5]=$dpTime;
// 	   		$line[8]=$distance;
// 	   		array_push($stop_table,$line);
// 	    }
// 	    //将表进行加工，算出出发时间
// 	    var_dump( AddRunTime($stop_table));
	    
// }catch(Execption $e){
// 	die();
// }

// /**
// *算出"两时间点"间的时间(分钟)
// */
// function abtime($startdate,$enddate){
// 	$dpTime=strtotime($startdate);
// 	$arrTime=strtotime($enddate);
// 	$time=date('H:i',$arrTime-$dpTime);
// 	$hour=mb_substr($time,0,2);
// 	$minute=mb_substr($time,3);
// 	$otm=(int)$hour*60+(int)$minute;
// 	return $otm;
// }
// /**
// *加上从出站到本站的时间runTime、overTime(分钟)
// */
// function AddRunTime($table){
// 	$temp_time=0;
// 	for($i=0;$i<count($table);$i++)
// 	{
// 		if($i==0){//起始站
// 			$table[0][6]=0;//overTime
// 			$table[0][7]=0;//runTime
// 			$temp_time=$table[0][7];//runTime
// 		}else{
// 			$big=abtime($table[$i-1][5],$table[$i][5]);//'dpTime''dpTime'
// 			$small=abtime($table[$i][4],$table[$i][4]);//'arrTime''dpTime'
// 			$table[$i][6]=$small;//'overTime'
// 			$table[$i][7]=$big+$temp_time;//'runTime'
// 			$temp_time=$table[$i][7];//'runTime'
// 		}

// 	}

// 	return $table;
// } 
    
?>