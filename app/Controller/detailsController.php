<?php
    namespace app\Controller;
    use \lib\core\DB;
    class details{

        private $recommend = '';
        private $busData = [];
        private $trainData = [];
        private $flightData = [];
        private $shipData = [];
        private $interchange = [];

        public function index(){
            $this->display();
        }

        private function bus(){
            $mod = M('bus');
            $this->busData = $mod->get_bus($_GET['start'],$_GET['end'],"price");
        }

        private function trains(){        
            $trains_obj= M('TrainsPrice');
            $this->trainData = $trains_obj->Search_trains($_GET['start'],$_GET['end']);
        }

        private function flight(){
            $flight_obj= M('flight');
            $this->flightData = $flight_obj->searchFlight($_GET['start'],$_GET['end']);
        }

        private function ship(){
            $mod = M('ship');
            $this->shipData = $mod->pan($_GET['start'],$_GET['end']);
        }

        private function recommend(){
            if(!empty($this->trainData)){
                $train_M=M('TrainsPrice');
                $trainData_Price=$train_M-> PriceUpSort($this->trainData);
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

        private function get_interchange_line($citys,$i,$j){
            // var_dump($citys);p($i.','.$j);
            $bus_mod = M('bus');
            $train_mod = M('TrainsPrice');
            $flight_mod = M('flight');
            $ship_mod = M('ship');
            if($j == 0){
                foreach ($citys as $city) {
                    $temp = $bus_mod->get_bus($_GET['start'],$city,"price")[0];
                    if($i==0)
                        $second = $bus_mod->get_bus($city,$_GET['end'],"price")[0];
                    else if($i==1)
                        $second = $train_mod->Search_trains($city,$_GET['end'])[0];
                    else if($i==2)
                        $second = $flight_mod->searchFlight($city,$_GET['end'])[0];
                    else
                        $second = $ship_mod->pan($city,$_GET['end'])[0];
                    if((!empty($temp)) && (!empty($second))){
                        $this->interchange[] = array('first'=>$temp,'second'=>$second);
                    }
                }
            }else if($j == 1){
                foreach ($citys as $city) {
                    $temp = $train_mod->Search_trains($_GET['start'],$city)[0];
                    if($i==0)
                        $second = $bus_mod->get_bus($city,$_GET['end'],"price")[0];
                    else if($i==1)
                        $second = $train_mod->Search_trains($city,$_GET['end'])[0];
                    else if($i==2)
                        $second = $flight_mod->searchFlight($city,$_GET['end'])[0];
                    else
                        $second = $ship_mod->pan($city,$_GET['end'])[0];
                    if((!empty($temp)) && (!empty($second))){
                        $this->interchange[] = array('first'=>$temp,'second'=>$second);
                    }
                }
                // if(!empty($second_lines))
                //     {var_dump($second_lines);p("");p("");}
            }else if($j == 2){
                foreach ($citys as $city) {
                    $temp = $flight_mod->searchFlight($_GET['start'],$city)[0];
                    if($i==0)
                        $second = $bus_mod->get_bus($city,$_GET['end'],"price")[0];
                    else if($i==1)
                        $second = $train_mod->Search_trains($city,$_GET['end'])[0];
                    else if($i==2)
                        $second = $flight_mod->searchFlight($city,$_GET['end'])[0];
                    else
                        $second = $ship_mod->pan($city,$_GET['end'])[0];
                    if((!empty($temp)) && (!empty($second))){
                        $this->interchange[] = array('first'=>$temp,'second'=>$second);
                    }
                }
            }else if($j == 3){
                foreach ($citys as $city) {
                    $temp = $ship_mod->pan($_GET['start'],$city)[0];
                    if($i==0)
                        $second = $bus_mod->get_bus($city,$_GET['end'],"price")[0];
                    else if($i==1)
                        $second = $train_mod->Search_trains($city,$_GET['end'])[0];
                    else if($i==2)
                        $second = $flight_mod->searchFlight($city,$_GET['end'])[0];
                    else
                        $second = $ship_mod->pan($city,$_GET['end'])[0];
                    if((!empty($temp)) && (!empty($second))){
                        $this->interchange[] = array('first'=>$temp,'second'=>$second);
                    }
                }
            }
        }

        function show(){
            if(!empty($_GET['start']) && !empty($_GET['end'])){
                $this->bus();
                $this->trains();
                $this->flight();
                $this->ship();
                $this->recommend();
                // var_dump($this->recommend);
                // die;
                if(!empty($this->recommend[0]))
                    echo json_encode($this->recommend,JSON_UNESCAPED_UNICODE);
            }
        }

        function display(){
            $data = [];
            if(!empty($_GET['start']) && !empty($_GET['end'])){
                $this->bus();
                $this->trains();
                $this->flight();
                $this->ship();
                $data[] = $this->busData;
                $data[] = $this->trainData;
                $data[] = $this->flightData;
                $data[] = $this->shipData;
                // p($this->recommend);
                // die;
            }
            // var_dump($data);die;
            $view = V('details');
            if(empty($this->busData) && empty($this->trainData) && empty($this->flightData) && empty($this->shipData)){
                $view->display('detailsHtml/transCity','');
            }else{
                $view->display('detailsHtml/details',$data);
            }
        }

        function interchange(){
            $mod = M('centerCity');
            $result = $mod->getCenterCity();
            // var_dump($result[0]);die;
            if(is_array($result[0]))
                for ($i = 0;$i < count($result);$i ++) {
                    $all_citys = $result[$i];
                    for($j = 0;$j < count($all_citys);$j ++){
                        $this->get_interchange_line($all_citys[$j],$i,$j);
                    }
                }
            else{
                foreach ($result as $city) {
                    $bus_mod = M('bus');
                    $train_mod = M('TrainsPrice');
                    $flight_mod = M('flight');
                    $ship_mod = M('ship');
                    $first = $bus_mod->get_bus($_GET['start'],$city,"price")[0];
                    $second = $bus_mod->get_bus($city,$_GET['end'],"price")[0];
                    if(!empty($second))
                        $this->interchange[] = array("first" => $first,"second" => $second);
                    $second = $train_mod->Search_trains($city,$_GET['end'])[0];
                    if(!empty($second))
                        $this->interchange[] = array("first" => $first,"second" => $second);
                    $second = $flight_mod->searchFlight($city,$_GET['end'])[0];
                    if(!empty($second))
                        $this->interchange[] = array("first" => $first,"second" => $second);
                    $second = $ship_mod->pan($city,$_GET['end'])[0];
                    if(!empty($second))
                        $this->interchange[] = array("first" => $first,"second" => $second);
                    // p($this->interchange);die;
                }
            }
            // var_dump($this->interchange);die;
            echo json_encode($this->interchange,JSON_UNESCAPED_UNICODE);
        }
    }
?>