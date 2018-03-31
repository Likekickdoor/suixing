<?php
    namespace app\Controller;
    use \lib\core\DB;
    class details{

        function index(){

            $view = V('index');
            $view->display('index','






                    欢迎使用MyFrame






            ');
        }

        function bus(){
            $mod = M('bus');
            if(!isset($_GET['start']) || !isset($_GET['end'])){
                $this->index();
                return 0;
            }
            $data = $mod->get_bus($_GET['start'],$_GET['end'],'2018-03-15');
            $view = V('bus');
            $view->show_bus('bus',$data);
        }

        function trains(){
           
            $trains_obj= M('TrainsPrice');
            if(!isset($_GET['dpplace']) || !isset($_GET['arrplace'])){
                $this->index();
                return 0;
            }
            $datas= $trains_obj->Search_trains($_GET['dpplace'],$_GET['arrplace']);
            $view= V('trains');
            $view->show_trains($datas);
        }

        function flight(){
           
            if(!isset($_GET['dpplace']) || !isset($_GET['arrplace'])){
                $this->index();
                return 0;
            }
            
            
            $flight_obj= M('flight');
            //$flight_obj->select_line($_GET['dpplace'],$_GET['arrplace']);
            $datas= $flight_obj->searchFlight();
            var_dump($datas);
        }

        function stopApi(){
            if(!isset($_REQUEST['about_id']) || !isset($_REQUEST['dpSort'])||!isset($_REQUEST['arrSort'])){
                return 0;
            }
            $trains_obj=M('StopInfo');
            $trains_obj->get_stop_info($_REQUEST['about_id'],$_REQUEST['dpSort'],$_REQUEST['arrSort']);
        }

        
    }
?>