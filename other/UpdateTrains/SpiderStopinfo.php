<?php
// require_once('phpQuery/phpQuery.php');
// require_once('collect.php');
// require_once('train114.php');
// $pdo=new PDO('mysql:host=localhost;dbname=suixing','root','');
// SpiderStopinfo($pdo,'trains1');

function SpiderStopinfo($pdo,$trainsTable){
        /************从mysql trains表 Upstatus=1得到url数组**************/
        $sql ="SELECT `tid`,`trainNo` FROM `{$trainsTable}` WHERE Upstatus=1";
        $trains_obj=$pdo->query($sql);
        $trains=$trains_obj->fetchAll();
        $urls_train=[];
        $tids      =[];
        $trainNos  =[];
        foreach ($trains as $train) {
        //$url_train='http://www.huochepiao.com/dongche/checi_'.$train['trainNo'];
        $url_train='http://checi.114piaowu.com/'.$train['trainNo']; 
        array_push($tids, $train['tid']);
        array_push($trainNos, $train['trainNo']);
        array_push($urls_train, $url_train);
        }
        /**********for循环循环完就已经将stop_info.bin有最新车次*********/
          $temp_tids=[];
          $temp_trainNos=[];
          $temp_urls_train=[];
          $Upsum=count($urls_train);
        for($j=0;$j<$Upsum;$j++){
          array_push($temp_tids,$tids[$j]);
          array_push($temp_urls_train,$urls_train[$j]);
          array_push($temp_trainNos,$trainNos[$j]);
          //一次性在trains表中取出了要更新的车次，再逢5成一组
          if($j%4==0||$j==$Upsum-1){
            
            $getpage    = new OpenURLClass($temp_urls_train);
            $contexts   = $getpage->collect();//同时打开这些页面，得到一组url的内容
            foreach ($contexts as $key => $context){
                /*火车票网页面处理
                $TrainTable  =new trainsTable();
                $text_box    =$TrainTable->phpqueryReturn($context,$charset='gb2312',$selector='div.lefttbox table:eq(1) tr');
                if($text_box!=false){//打开的url有想要内容
                $table       =$TrainTable->returnDeal($text_box,$temp_tids[$key]);//将其此url的tid=1 . 2 .3等传入
                */
                $TrainTable  =new trainsTable114;
                $text_box    =$TrainTable->phpqueryReturn114($context);
                if($text_box){
                $table       =$TrainTable->returnDeal114($text_box,$temp_tids[$key],$temp_trainNos[$key]);
                Savefile('station_stop.bin',$table);  
                }else{
                  $nofind_hwnd=fopen('nofind.bin', 'a');
                  fwrite($nofind_hwnd, 'tid:'.$temp_tids[$key].'trainNo:'.$temp_urls_train[$key]."\r\n");
                  fclose($nofind_hwnd);
                }
                $TrainTable=null;
            }
            // print_r($temp_tids);echo '<br/>';
            // print_r($temp_urls_train);echo '<br/>';
            printf("\r\n");
            $temp_tids=[];
            $temp_trainNos=[];
            $temp_urls_train=[];
            $getpage=null;
            sleep(mt_rand(3,4));
          }

        }
  return true;
}        
/**
*@param 负责收集停站表
*/
class trainsTable
{
    
    function phpqueryReturn($context,$charset='utf-8',$selector='div.lefttbox table:eq(1) tr'){
        phpQuery::$defaultCharset=$charset;//把phpQuery类定义其默认编码方式
        $document   = phpQuery::newDocumentHTML($context);
        //因为这个对象可以有读取节点相应信息的方法，如果find   html。text 等方法；
        $doc        = phpQuery::pq("");//对象
        $text_box   = $doc->find($selector);
        // echo "text_box数量为:".count($text_box).'<br/>';
        if(count($text_box)==0||$text_box==null) { return false; }
        return $text_box;
    }
    
    function returnDeal($text_box,$about_id){

        $stationListS=[];//装取停站表
        foreach ($text_box as $i => $value) {
            # code...第一排tr不要
           if($i==0) continue;


            $trainNo  =pq($value)->find("td:eq(0)")->text();//echo "$trainNo <-->";//G2151
            $stationSort=pq($value)->find("td:eq(1)")->text();//echo "$stationSort <-->";//1站
            $stationName=pq($value)->find("td:eq(2)")->text();//echo "$stationName <-->";//长沙南
            $arriveTime =pq($value)->find("td:eq(3)")->text();//始发站、10：51
           if($arriveTime==='始发站'){$arriveTime='00:00';}
             //echo "$arriveTime <-->";
            $startTime  =pq($value)->find("td:eq(4)")->text();//终点站、17:00
           if($startTime==='终点站'){$startTime=$arriveTime;}
             //echo "$startTime <-->";
            $overTime   =pq($value)->find("td:eq(5)")->text();
            $overTime   =get_overTime($overTime,$key='分钟');
             //echo "$overTime <-->";//2分钟
            $runTime=pq($value)->find("td:eq(6)")->text();//从出发的时间到现在 1时20分
            $runTime=get_runTime($runTime,'/[小时分]/');
             //echo "$runTime <-->";
            $distance   =pq($value)->find("td:eq(7)")->text();//echo "$distance <br/>";//里程56公里
            
           

      $stationList=[];//空数组以存第一排站点的数据
      array_push($stationList, $about_id);//将传入的车次tid 也存入每一行中
      array_push($stationList, $trainNo);//G2151
      array_push($stationList, $stationSort);//第1站
      array_push($stationList, $stationName);//长沙南
      array_push($stationList, $arriveTime);//11:00
      array_push($stationList, $startTime);//12:30
      array_push($stationList, $overTime);//2(分钟)
      array_push($stationList, $runTime);//120(分钟)
      array_push($stationList, $distance);//56（公里）
      
      
      
      array_push($stationListS, $stationList);
      }
      // echo 'stationListS数量'.count($stationListS);
     // var_dump($stationListS);
     return $stationListS; 
    }
        
}
    



/**
*@param 得到overtime,分割2分钟
*返回得到int整数型
*/
   function get_overTime($overTime,$key='分钟'){
        $pos =strpos($overTime, $key);
        $overTime=mb_substr($overTime,0,$pos,'utf-8');
        return $overTime;
    }

/**
*@param 得到runTime,换算成分的
*返回整数int型数据
*/
   function get_runTime($str,$pattern){
        $result = preg_split($pattern, $str);
        //0、6有，6可能没有当只有‘分’时
        $result_sum=count($result);
        if($result_sum==4){
          return $result[0];
        }else if($result_sum==7){
          return 60*$result[0];
        }else{
          return 60*$result[0]+$result[6];
        }
    }

/**
*@param 存入文本文件
*/
   function Savefile($filename,$table){
      $table      =serialize($table);
      $file_hwnd  =fopen($filename, 'a+');
      fwrite($file_hwnd, $table."\r\n");
      return true;
   }
?>