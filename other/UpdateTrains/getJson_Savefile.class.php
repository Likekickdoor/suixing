<?php
set_time_limit(0);
require_once('collect.php');//爬虫收集器
require_once('phpQuery/phpQuery.php');
require_once('SpiderStopinfo.php');//爬虫页面处理器
require_once('SaveStopToSql.php');

$pdo=new PDO('mysql:host=localhost;dbname=suixing','root','');
$pdo->query('set names utf8');
/**
* 将从12306得到的火车Json格式数据存入train_All.json文件
*/
class getTrainsJsonClass extends OpenURLClass
{
	private $filepath;
	private $Jsonpath;
	function __construct($putfilepath=null,$putJsonpath=null)
	{
		$this->filepath=$putfilepath;
		$this->Jsonpath=$putJsonpath;
		if($this->filepath==null||$this->Jsonpath==null){
			die('没有传入json接口');
			return false;
		}
	}
	/**
	*从12306得到json,放入文件中,以便后续执行 
	*/
	public function Begin_TrainsJson(){
		$json=self::curl_get_file($this->filepath);
		if($json!=''){
			file_put_contents($this->Jsonpath, $json, true);
			// $fp=fopen('train_list2.json', 'w');
			// fwrite($fp, $json);
			return true;
		}else{
			return false;
		}
	}
}

/**
* 将从12306得到的火车Json格式数据存入json文件后，访问此文件并收集这些火车编号名存入X_trains.bin文件
*/
class ReadJsonFileClass
{
	private  $filePath;
	private  $SavefilePath;
	private  $train_types;
	function __construct($filePath=null,$SavefilePath=null,$train_types=null)
	{
		if($filePath==null||$SavefilePath==null||$train_types==null){
			die('构造函数没有输入参数！');
			return false;
		}
		$this->filePath=$filePath;
		$this->SavefilePath=$SavefilePath;
		$this->train_types=$train_types;
	}

	/**
	*执行整个类的功能
	*/
	public function Begin_Save(){
	$Putjson=self::getOnejson($this->filePath);
	$sum_trains=self::get_trains_list($this->SavefilePath,$this->train_types,$Putjson);
	return $sum_trains;
	}

	/**
	*json文件的路径
	*@param $filePath存入文件路径
	*/
	private static function getOnejson($filePath){
	/*截取一天的列车编号*/
	$file_hwnd=fopen($filePath, 'r');
	$json=fgets($file_hwnd);
	//截掉变量名var train_list=
	// $Leftkuohao=mb_strpos($json,'{');
	$len=strlen('var train_list =');
	$json_begin_pos=$len;
	$mode=mb_substr($json,$json_begin_pos+1,6);
	$mode=','.$mode;
	$json_end_pos=mb_strpos($json,$mode);
	$json=mb_substr($json,$json_begin_pos,$json_end_pos-$len).'}';
	fclose($file_hwnd);
	return $json;
	}

	/**
	*收集到车次编号到文件X_train.bin文件中
	*@param $SavefilePath存入文件路径
	*@param $train_types车次遍历先后
	*@param $json 传入进来的json数据串
	*/
	private static function get_trains_list($SavefilePath,$train_types,$json){
	$filehwnd=fopen($SavefilePath, 'w');
	$json_obj=json_decode($json);
	//json_obj一开始是时间-->json是各种列车的‘数组对象’
	foreach ($json_obj as $json) {
	// var_dump($json);O是其他类型车、D是动车、G是高铁...
	$sum_trains=0;
	foreach ($train_types as $train_type) {
		$X_trains=$json->$train_type;
		// var_dump($X_trains);//$X_trains是数组
		foreach ($X_trains as $key => $X_train) {
		#D_train是‘对象数组’不一样！
		// echo "$key:  >".$X_train->station_train_code.'<br/>';
		fwrite($filehwnd, $X_train->station_train_code."\r\n");
		}
		$sum_trains=$sum_trains+($key+1);		
	}
	// exit();
	break;
  }
  fclose($filehwnd);
  return $sum_trains;
	}
}

/**
*@param 存入 数据库suixing 中的train 表,把文本文件中的车次按 K/T/Z->G/D/C->QT车次存入
*@param pattern 要搜索的模式，字符串类型。 
*@param subject 输入字符串。 
*/
function SaveTrainsSql($arr_trains,$pdo){
    // $pdo=new PDO('mysql:host=localhost;dbname=trains','root','');
    // $pdo->query('set names utf8');
    foreach ($arr_trains as $value) {
    # 打开车次编号文件
    $filename="$value";
    $file_hwnd= fopen($filename,"r");

    while (!feof($file_hwnd)) {
        $get=fgets($file_hwnd);
        $get=trim($get);
        if($get==''){
         break;
        }
        $get=mb_convert_encoding($get, 'UTF-8','GB2312,UTF-8,ASCII');
        $pattern = '/[\(\)\-]/';
        $result = preg_split($pattern, $get);
        // echo "NO:{$i} ".$result[0].'-'.$result[1].'-'.$result[2].'<br/>';
        $sql="INSERT into `trainsx` (`tid`,`trainNo`,`dptStationName`,`arrStationName`) values(null,'$result[0]','$result[1]','$result[2]')";
        $pdo->exec($sql);
    }
    printf("Finlish insert trainsx-tables from txt: %s\r\n",$value);
    fclose($file_hwnd);
 }
 return true;
}

/**
* 取得trainx里的信息,再对比trains中的数据没有则添加在trains表里,测试阶段使用train1表
*@param 输入trains数据库表名
*/
function UpdateTrains($trains1,$pdo){
	// $startTime = microtime(true);
	// $pdo=new PDO('mysql:host=localhost;dbname=suixing','root','');
	// $pdo->query('set names utf8;');
	$sql='SELECT count(tid) as rows FROM trainsx';
	$stmt=$pdo->query($sql);
    $sum_trains=$stmt->fetchAll(PDO::FETCH_ASSOC);
    $sum_trains=$sum_trains[0]['rows'];//具体数值行数
    // echo $sum_trains;
	// exit();
	$sql="SELECT `trainNo`,`dptStationName`,`arrStationName` FROM trainsx";
	$stmt=$pdo->query($sql);
    $trainNos=$stmt->fetchAll(PDO::FETCH_ASSOC);
    // var_dump($trainNos);exit();
    $i=0;
	for($i=0;$i<$sum_trains;$i++){
		# 取出一行数据有主键+编号,并在旧表中进行查询
		$trainNo=$trainNos[$i]['trainNo'];
		$dptStationName=$trainNos[$i]['dptStationName'];
        $arrStationName=$trainNos[$i]['arrStationName'];
        $sql="INSERT INTO {$trains1}(`trainNo`,`dptStationName`,`arrStationName`,`status`,`Upstatus`) SELECT '{$trainNo}','{$dptStationName}','{$arrStationName}',1,1 FROM DUAL WHERE NOT EXISTS(SELECT trainNo FROM {$trains1} WHERE trainNo='{$trainNo}') limit 1";
        // echo $sql;
        $stmt=$pdo->exec($sql);
    	// $rows=$stmt->rowCount();//受影响条数
	}
	return true;	
	// $endTime = microtime(true);
	// printf("Take time is :%f\r\n",round($endTime-$startTime,3));
}

/**
*@param 在最后清理文件内容,传入文件名数组
*/
function CleanFile($arrFile){
	foreach ($arrFile as $key => $value) {
		$fpClean=fopen($CleanFlie,'w');
		fclose($fpClean);
	}
return true;	
}



// exit('not detele exit function');





try{
	/*抓取12306列车号信息json，存本trains_All.bin*/
	$filepath = 'https://kyfw.12306.cn/otn/resources/js/query/train_list.js?scriptVersion=1.0';
	$url=new getTrainsJsonClass($filepath,'trains_All.json');
	$statue=$url->Begin_TrainsJson();

	if($statue==true){
	/*将json存本，取出列车存入X_trains.bin*/
	$train_types=array(
	'0'=>'K',
	'1'=>'T',
	'2'=>'Z',
	'3'=>'G',
	'4'=>'D',
	'5'=>'C',
	'6'=>'O',
	);
	$train_list=new ReadJsonFileClass('trains_All.json','X_trains.bin',$train_types);
	$sum_trains=$train_list->Begin_Save();
	echo 'This have'.$sum_trains.'in X_trains.bin  ';
	/*正式存数据库，到表trainsx中*/
	$arr_trains=array(
	'X'=>'X_trains.bin'
	);
	echo SaveTrainsSql($arr_trains,$pdo)==true?'Success':'default';
	/*更新数据库*/
	$Insert_over=UpdateTrains('trains1',$pdo);//表名
		if($Insert_over==true){
			//选取Upstatus=1的来进行爬新的车次
			$stop_infoBin=SpiderStopinfo($pdo,'trains1');//将从trains表取出要更新的,抓来存于Station_stop.bin中
			$stop_infoSql=SaveStopToSql($pdo);//将Station_stop.bin存于数据库
		 	//将表train中Upstatus全改为0,清空station_stop.bin序列化存好的停站信息/nofind.bin车次爬取网站没有/new_look.bin因为生僻字报废
		 	CleanFlie(array('trains_All.json','X_trains.bin','station_stop.bin','nofind.bin','new_look.bin'));
		 	//将trainsx表TRUNCATE空，下次使用，最后将trains表更新的数据变为旧的数据状态Upstatus=1  --> Upstatus=0
			$sql='UPDATE trains SET Upstatus=0 WHERE Upstatus=1';
			$pdo->exec($sql);
			$sql='TRUNCATE TABLE trainsx';
			$pdo->exec($sql);
		 	exit('finlish all thing!');
		}
	}else{
		throw new Exception('Can not Update,don not have trains_All.json文件',1);
	}
}catch(Execption $e){
	echo $e->getMessage();
}		
?>