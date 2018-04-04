<?php
    namespace app\Controller;
    class ajax{

        function getStation(){
            $mod = M('station');
            $data = $mod->get_station();
            foreach ($data as $key) {
                $pingyin = str_replace(" ","",\PY::encode($key['name'],""));
                echo ($key['name'].",".$pingyin.";");
            }
        }

    }
?>