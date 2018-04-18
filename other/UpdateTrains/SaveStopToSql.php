<?php
// $pdo=new PDO('mysql:host=localhost;dbname=suixing','root','');
// $pdo->query('set names utf8');
// SaveStopToSql($pdo);
/**
*@param 读取某行的文件制定函数
*/
function SaveStopToSql($pdo){
      $file='station_stop.bin';
      $i = 0; // 起始行数
      $handle = @fopen($file, "r");
      if ($handle) {
          while (!feof($handle)) {
              ++$i;
              $returnTxt = fgets($handle);
              $infos=unserialize($returnTxt);//将上列取出的停站表逆序列化得二维数组
              // var_dump($infos);exit();
              //检查是否为有效的车次表，有部分生僻车站，断掉信息，那版车次停站信息报废，记录下tid(about_id) 和 车次名
              $array_pai=count($infos)-1;
              if($infos[$array_pai][4]==''||$infos[$array_pai][5]==''){
                  $file_hwnd=fopen('new_look.bin','a' );
                  fwrite($file_hwnd,$infos[$array_pai][0].'--'.$infos[$array_pai][1]."\r\n");
                  fclose($file_hwnd);
                  printf("this infos need recover find Stop_info,%d--%s\n",$infos[$array_pai][0],$infos[$array_pai][1]);
                  continue;
              }
              //将有效的，逆序列化车次表信息，得行信息，进行进库处理
              $valStr ="INSERT INTO `station_stop` (`about_id`, `trainNo`, `stationSort`, `stationName`, `arriveTime`, `startTime`, `overTime`, `runTime`, `distance`) VALUES ";
              foreach ($infos as $j=> $info) {
                 $about_id=$info[0];
                 $trainNo=$info[1];
                 $stationSort=$info[2];
                 $stationName=$info[3];
                 $arriveTime=$info[4].':00';
                 $startTime=$info[5].':00';
                 $overTime=$info[6];
                 $runTime=$info[7];
                 $distance=$info[8];
                 if($j==0){
                 $valStr.="('$about_id', '$trainNo', '$stationSort', '$stationName', '$arriveTime', '$startTime', '$overTime', '$runTime', '$distance')";
                 }else{
                 $valStr.=",('$about_id', '$trainNo', '$stationSort', '$stationName', '$arriveTime', '$startTime', '$overTime', '$runTime', '$distance')";
                 }                 
              }
              $return=$pdo->exec($valStr);
              if($return==0){
                printf("the error line is:%d\n",$j);
              }      
          }
          fclose($handle);
      }
  return true;
}
    

?>