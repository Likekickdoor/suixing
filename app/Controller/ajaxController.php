<?php
    namespace app\Controller;
    class ajax{

        private $start;
        private $end;
        private $busData = '';
        private $trainData = '';
        private $flightData = '';
        private $shipData = '';
        private $centerCity = '';

        function bus(){
            $mod = M('bus');
            $this->busData = $mod->get_bus($_GET['start'],$_GET['end'],"price");
        }

        function trains(){        
            $trains_obj= M('TrainsPrice');
           $this->trainData = $trains_obj->Search_trains($_GET['start'],$_GET['end']);   
        }

        function flight(){
            $flight_obj= M('flight');
            $this->flightData = $flight_obj->searchFlight($_GET['start'],$_GET['end']);
        }

        function ship(){
            $mod = M('ship');
            $this->shipData = $mod->pan($_GET['start'],$_GET['end']);
        }

        function getStation(){
            $mod = M('station');
            $data = $mod->get_station();
            $size = count($data);
            $value = "";
            foreach($data as $key) {
                $value .= $key['stations'];
            }
            p($value);
        }

        function getCenterCity(){
            if(!empty($_GET['start']) && !empty($_GET['end'])){
                $this->start = $_GET['start'];
                $this->end = $_GET['end'];
                $this->bus();
                $this->trains();
                $this->flight();
                if(empty($this->busData) && empty($this->trainData) && empty($this->flightData) && empty($this->shipData)){
                    $mod = M('centerCity');
                    $this->centerCity = $mod->getCenterCity();
                    echo $this->centerCity;
                }else{
                    echo "true";
                }
            }
        }

        function recommend(){
            if(is_array($this->trainData)){
                $train_M=M('TrainsPrice');
                // $trainData_Price=$train_M-> PriceUpSort($this->trainData);
                $trainData_Price=$train_M->VaildPriceSort($this->trainData);
                $trainData_Time=$train_M-> TimeUpSort($this->trainData);
                
                $trainDatas=[];
                array_push($trainDatas,$trainData_Price);
                array_push($trainDatas,$trainData_Time);
            }
            $mod = M('recommend');
            $this->recommend = $mod->getRecommend($this->busData,$trainDatas,$this->flightData,$this->shipData);
            // p($this->recommend);
            // die;
        }

        function show(){
            if(!empty($_GET['start']) && !empty($_GET['end'])){
                $this->bus();
                $this->trains();
                $this->flight();
                $this->ship();
                $this->recommend();                
            }
        }

        function chooseFristCity(){
            $mod = M('chooseCity');
            $data = [];
            array_push($data,$mod->getFristCity());
            $this->show();
            array_push($data,$this->recommend);
            p($data[0]);
            die;
            header('Content-Type: application/json; charset=utf8'); 
            echo json_encode($data);
        }

        function chooseSecondCity(){
            $mod = M('chooseCity');
            $data = [];
            array_push($data,$mod->getSecondCity());
            $this->show();
            array_push($data,$this->recommend);
            p($data[0]);
            die;
            header('Content-Type: application/json; charset=utf8'); 
            echo json_encode($data);
        }
        /**
        *@param 停站信息Dots调用模型及方法 
        */
        function station_stop(){
            
            if(empty($_REQUEST['about_id'])||empty($_REQUEST['dpSort'])||empty($_REQUEST['arrSort'])){
                return null;
            }else{
                $station_stop_obj=M('StopInfo');
                $station_stop=$station_stop_obj->get_stop_info($_REQUEST['about_id'],$_REQUEST['dpSort'],$_REQUEST['arrSort']);
            }
        }

        /**
        *@param 停站信息Winows调用模型及方法 
        */
        function station_stopWin(){
            
            if(empty($_REQUEST['about_id'])||empty($_REQUEST['dpSort'])||empty($_REQUEST['arrSort'])){
                return null;
            }else{
                $station_stop_obj=M('StopInfo');
                $station_stop=$station_stop_obj->get_stop_infoWin($_REQUEST['about_id'],$_REQUEST['dpSort'],$_REQUEST['arrSort']);
            }
        }

    }
?>