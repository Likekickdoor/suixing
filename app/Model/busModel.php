<?php
    namespace app\Model;
    use lib\core\DB;
    class bus{
        private $data = [];
        private $start;
        private $end;
        private $start_id = 0;
        private $end_id = 0;
        
        function init($start,$end){
            $this->start = $start;
            $this->end = $end;
            $this->get_id();
        }
        //获取输出信息
        function print_line($lines){
            foreach ($lines as $line) {
                // if($line['time']==0){
                //     $line['time'] = "未知";
                //     $line['arrive_time']="未知";
                // }
                $temp = explode('.',$line['time']);
                $hour = $temp[0]."时";
                $min = ($temp[1] / 100 * 60)."分";
                $line['time'] = $hour.$min;
                array_push($this->data,$line);
            }
        }
        //获取站点id
        function get_id(){
            $result = DB::find("city","city like '%{$this->start}%'");
            if(DB::$rowcount > 0){
                $this->start_id = $result['id'];
                $result = DB::find("station","name='{$this->end}'");
                if(DB::$rowcount > 0)
                    $this->end_id = $result['id'];
            }else{
                $this->start_id = DB::find("station","name='{$this->start}'")['id'];
                $result = DB::find("city","city like '{$this->end}'");
                if($result > 0){
                    $this->end_id = $result['id'];
                }else{
                    $result = DB::find("station","name='{$this->end}'");
                    if(DB::$rowcount > 0)
                        $this->end_id = $result['id'];
                }
            }
            // if($this->start_id == NULL || $this->end_id == NULL)
            //     return $this->data;
        }
        //获取线路信息
        function get_bus($start,$end,$order){
            $this->data = [];
            $this->init($start,$end);
            DB::find("city_to_station","city_id={$this->start_id} and station_id={$this->end_id}");
            if(DB::$rowcount > 0){
                $lines = DB::findAll("line","start_station_id={$this->start_id} and end_station_id={$this->end_id} order by $order");
                if($lines != NULL){
                    $this->print_line($lines);
                }else{
                    $this->data = [];
                }
            }else{
                // $this->get_interchange($order);
                $this->data = [];
            }
            // var_dump($this->data);p("");p("");
            return $this->data;
        }
    }
?>