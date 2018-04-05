<?php
    namespace app\Controller;
    class ajax{

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

    }
?>