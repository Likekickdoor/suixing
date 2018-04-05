<?php
    namespace app\Controller;
    use \lib\core\DB;
    class details{

        private $busData = '';
        private $trainData = '';
        private $flightData = '';
        private $shipData = '';

        function bus(){
            $mod = M('bus');
            $this->busData = $mod->get_bus("price");
        }

        function trains(){        
            $trains_obj= M('TrainsPrice');
            $this->trainData = $trains_obj->Search_trains($_GET['dpplace'],$_GET['arrplace']);
        }

        function flight(){
            $flight_obj= M('flight');
            $this->flightData = $flight_obj->searchFlight();
        }

        function show(){
            if(isset($_GET['start']) && isset($_GET['end'])){
                $mod = M('centerCity');
                $isInterchange = $mod->getCenterCity();
                p($isInterchange);
                die;
                $this->bus();
                // $this->trains();
                // $this->flight();
            }
            $view = V('details');
            $view->display('detailsHtml/details',$this->busData,$this->trainData,$this->flightData,$this->shipData);
        }
    }
?>