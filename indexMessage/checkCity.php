<?php
/**
 * 检查用户输入的对应那些城市
 */
session_start();

$cur_dir = dirname(__FILE__); //获取当前文件的目录
chdir($cur_dir); //把当前的目录改变为指定的目录。
 require_once('../setIndex/search_modth.php');
 
 

 class checkCity {
    private $searchObject;
    private $pdoObject;
    /**
     * 构造方法，建立对象
     */
    public function __construct(){
        $this->searchObject = new searchModth();
        $this->pdoObject = new pdoSql();
    }
     /**
      * 得到用户输入的信息，查找出能对应的城市
      */
      
      public function checkCity_one($city){
        $pingyin_data = array();
        $chinese_data = array();
        preg_match_all('/[a-zA-Z]+/',$city,$pingyin);
        $chinese = preg_replace('/[0-9a-zA-Z]+/',"",$city);

        if(!empty($pingyin)){
            foreach($pingyin[0] as $value){
                $pingyin_data_less = $this->searchObject->selectTocity($value,"all");
                $pingyin_data = array_merge($pingyin_data,$pingyin_data_less);
            }
        }

        if(!empty($chinese)){
            $chinese_data = $this->searchObject->selectTocity($chinese);
        }
        $citydata = array_merge($chinese_data,$pingyin_data);
        
        for($i=0;$i<count($citydata);$i++){                     //冒泡排序进
            for($j=0;$j<count($citydata)-1;$j++){
                if($citydata[$j][2]<$citydata[$j+1][2]){
                    
                    $station_id = $citydata[$j+1][0];
                    $station = $citydata[$j+1][1];
                    $matchNum = $citydata[$j+1][2];

                    $citydata[$j+1][0] = $citydata[$j][0];
                    $citydata[$j+1][1] = $citydata[$j][1];
                    $citydata[$j+1][2] = $citydata[$j][2];

                    $citydata[$j][0] = $station_id;
                    $citydata[$j][1] = $station;
                    $citydata[$j][2] = $matchNum;
                }

                if($citydata[$j][2]==$citydata[$j+1][2]){
                    if(mb_strlen($citydata[$j][1])>mb_strlen($citydata[$j+1][1])){

                    $station_id = $citydata[$j+1][0];
                    $station = $citydata[$j+1][1];
                    $matchNum = $citydata[$j+1][2];

                    $citydata[$j+1][0] = $citydata[$j][0];
                    $citydata[$j+1][1] = $citydata[$j][1];
                    $citydata[$j+1][2] = $citydata[$j][2];

                    $citydata[$j][0] = $station_id;
                    $citydata[$j][1] = $station;
                    $citydata[$j][2] = $matchNum;
                    }
                }
            }
        }
        
        return $citydata;
      }

      

      /**
       * 通过前端数据查找一个城市数据
       */
      public function select_City(){
          if(!empty($_POST['city'])){
            $checkCity = str_replace(array("'",'"',"，","“","\\","/"," ","\t\n","\n","\t"),"",$_POST['city']);      //去掉字符的引号和空格
            $s_city = $this->checkCity_one($checkCity);
           
            
            if(count($s_city)>5){
                for($i=0;$i<5;$i++){

                    $city_one = str_replace(array("（","）"," ","\t\n","\n","\t","市","省","特别行政区","县"),"",$s_city[$i][1]);
                    $city_one = preg_replace('|[0-9a-zA-Z/]+|',"",$city_one);
                    $selectCity[] = $city_one;
                }
            }else{
                for($i=0;$i<count($s_city);$i++){
                    $city_one = str_replace(array("（","）"," ","\t\n","\n","\t","市","省","特别行政区","县"),"",$s_city[$i][1]);
                    $city_one = preg_replace('|[0-9a-zA-Z/]+|',"",$city_one);
                    $selectCity[] = $city_one;
                }

            }

            if(empty($selectCity)){
                $selectCity[]='未找到匹配城市';
            }
            
            $selectCity = array_unique($selectCity);
            foreach($selectCity as $value){
                $one_selectCity[] = $value;
            }

            echo json_encode($one_selectCity,JSON_UNESCAPED_UNICODE);//JSON_UNESCAPED_UNICODE让中文不编码
            exit;

          }else{
            die;
          }
      }
 }
 