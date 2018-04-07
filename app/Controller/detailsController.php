<?php
    namespace app\Controller;
    use \lib\core\DB;
    class details{

        private $recommend = '';
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

        function recommend(){
            if(is_array($this->trainData)){
                $train_M=M('TrainsPrice');
                $trainData_Price=$train_M-> PriceUpSort($this->trainData);
                $trainData_Time=$train_M-> TimeUpSort($this->trainData);
                $trainDatas=[];
                array_push($trainDatas,$trainData_Price);
                array_push($trainDatas,$trainData_Time);
            }
            $mod = M('recommend');
            $this->recommend = $mod->getRecommend($this->busData,$trainDatas,$this->flightData,$this->shipData);
        }

        function show(){
            if(!empty($_GET['start']) && !empty($_GET['end'])){
                $mod = M('centerCity');
                // $mod->set();
                // die;
                $this->bus();
                $this->trains();
                $this->flight();
                $this->ship();
                $this->recommend();
                header('Content-Type: application/json; charset=utf8'); 
                echo json_encode($this->recommend);
            }
        }

        function display(){
            $data = [];
            if(!empty($_GET['start']) && !empty($_GET['end'])){
                $this->bus();
                $this->trains();
                $this->flight();
                $this->ship();
                $this->recommend();
                array_push($data,$this->busData);
                array_push($data,$this->trainData);
                array_push($data,$this->flightData);
                array_push($data,$this->shipData);
            }
            $view = V('details');
            $view->display('detailsHtml/details',$data);
        }
    }
?>