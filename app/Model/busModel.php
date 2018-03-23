<?php
    namespace app\Model;
    use lib\core\DB;
    class bus{
        private $start;
        private $start_city_id = 0;
        private $start_station_id = 0;
        private $end;
        private $end_city_id = 0;
        private $end_station_id = 0;
        private $center;
        private $center_city_id = 0;
        private $center_station_id = 0;
        private $date;
        private $dbconfig;
        private $show;
        
        //获取线路信息
        function get_bus($start,$end,$date){
            $this->start = $start;
            $this->end = $end;
            $this->date = $date;
            $this->dbconfig = DB::$config;
            if(strpos($this->start,'市') or strpos($this->start,'县'))
                $this->start = substr($this->start,0,strlen($this->start) - 3);
            if(strpos($end,'市') or strpos($end,'县'))
                $this->end = substr($this->end,0,strlen($this->end) - 3);
            $this->get_id();
            $now_time = time();
            //判断是否能直达
            if($this->is_nonstop()){
                //如果不能直达,获取转乘线路
                $this->tranship();
            }else{
                $this->get_line($now_time);
            }
            return $this->show;
        }
        //获取站点和城市id
        function get_id(){
            $result = DB::find("city","city like '%$this->start%' and state=1");
            $this->start_city_id = DB::$rowcount ? $result['id'] : 0;
            $result = DB::find("station","name='$this->start' and state=1");
            $this->start_station_id = DB::$rowcount ? $result['id'] : 0;
            $result = DB::find("city","city like '%$this->end%' and state=1");
            $this->end_city_id = DB::$rowcount ? $result['id'] : 0;
            $result = DB::find("station","name='$this->end' and state=1");
            $this->end_station_id = DB::$rowcount ? $result['id'] : 0;
        }
        //获取直达线路
        function get_line($now_time){
            //检查线路是否存在
            $result = DB::findAll("line","start_station_id=$this->start_station_id and end_station_id=$this->end_station_id and state=1");
            if(DB::$rowcount > 0){    //如果线路已存在,直接输出
                $row = $result[0];
                $date_time = strtotime($row['date_time']);
                foreach($result as $row){
                    $date_time = strtotime($row['date_time']);
                    $time = (string)((int)($row['time']))."时".(string)(($row['time'] - (int)($row['time'])) * 60)."分";
                    $this->show .= "{$row['start_time']},{$row['arrive_time']},{$time},{$row['start_station_name']},{$row['end_station_name']},{$row['type']},{$row['price']};";
                }
            }else{
                $this->show .= "暂无汽车信息";
            }
        }
        //判断是否直达
        function is_nonstop(){
            DB::find("city_to_station","city_id=$this->start_city_id and station_id=$this->end_station_id and state=1");
            if(DB::$rowcount > 0)
                return TRUE;
            return FALSE;
        }
        //查询转乘线路
        function tranship(){
            //判断是否为城市
            if($this->start_city_id and $this->end_city_id){
                $this->city_to_city();
            }else if($this->start_city_id and $this->end_city_id == 0){
                if($this->end_station_id)
                    $this->city_to_station();
                else $this->show = "未查询到数据";
            }else if($this->start_city_id == 0 and $this->end_city_id){
                if($this->city_station_id)
                    $this->station_to_city();
                else $this->show = "未查询到数据";
            }else $this->show = "未查询到数据";
        }
        //城市到城市
        function city_to_city(){
            //获取出发城市能到达的所有站点
            $result = DB::findAll("city_to_station","city_id=$this->start_city_id and state=1");
            if(DB::$rowcount == 0){
                $this->show = "暂未查询到汽车票数据";
                return FALSE;
            }
            foreach($result as $r){
                //把站点当作城市,获取站点能到达的所有站点
                $this->center = DB::find("station","id={$r['station_id']} and state=1")['name'];
                if(strpos($this->center,'市') or strpos($this->center,'县'))
                    $this->center = substr($this->center,0,strlen($this->center) - 3);
                $this->center_city_id = DB::find("city","city like '%$this->center%' and state=1")['id'];
                if(DB::$rowcount == 0)
                    continue;
                //如果是城市,获取能到达的所有站点
                DB::find("city_to_station","city_id=$this->center_city_id and station_id=$this->end_station_id and state=1");
                if(DB::$rowcount){       //如果有站点能到达目的地
                    //检查路线是否存在
                    $line1 = DB::findAll("line","start_station_id=$this->start_station_id and end_station_id={$r['station_id']} and state=1");
                    if(DB::$rowcount > 0){
                        $line2 = DB::findAll("line","start_station_id={$r['station_id']} and end_station_id=$this->end_station_id and state=1");
                        if(DB::$rowcount > 0){
                            //输出路线
                            $this->print_line($line1);
                            $this->print_line($line2);
                        }
                    }
                }
            }
        }
        //小站到城市
        function station_to_city(){

        }
        //城市到小站
        function city_to_station(){

        }
        //输出路线
        function print_line($lines){
            foreach($lines as $line){   
                $time = (string)((int)($line['time']))."时".(string)(($line['time'] - (int)($line['time'])) * 60)."分";
                $this->show .= "{$line['start_time']},{$line['arrive_time']},$time,{$line['start_station_name']},{$line['end_station_name']},{$line['type']},{$line['price']};";
            }
        }
        //计算推荐线路
        function get_recommend_bus(){
            if(strpos($start,'市') or strpos($start,'县') or strpos($start,'州'))
                $start = substr($start,0,strlen($start) - 3);
            if(strpos($end,'市') or strpos($end,'县') or strpos($end,'州'))
                $end = substr($end,0,strlen($end) - 3);
            //查找出发地id
            $sql = "select * from station where name='$start' and state=1";
            $result = $this->con->find("station","name='$start' and state=1");
            $start_id = $result['id'];
            //查找目的地id
            $sql = "select * from station where name='$end' and state=1";
            $result = $this->con->find("station","name='$end' and state=1");
            $end_id = $result['id'];
            //查找线路
            $sql ="select * from line where start_station_id=$start_id and end_station_id=$end_id and state=1 order by start_time asc";
            $result = $this->con->findAll("line","start_station_id=$start_id and end_station_id=$end_id and state=1 order by start_time asc");
            $now_time = time();
            $next = '';
            $price = '';
            foreach($result as $row){
                $start_time = strtotime($date.$row['start_time'].":00");
                if($start_time - $now_time < 0)
                    continue;
                if($next == '' || $start_time < $next['start_time'])
                    $next = $row;
                if($price == '' || $row['price'] < $price['price'])
                    $price = $row;
            }
            $show = "<table class='bus'>";
            $show .= "<tr><th></th><th>出发时间</th><th>到达时间</th><th>时长</th><th>出发地</th><th>目的地</th><th>车型</th><th>价格</th></tr>";
            $show .= "<tr></tr>";
            $time = (string)((int)($next['time']))."时".(string)(($next['time'] - (int)($next['time'])) * 60)."分";
            $show .= "<tr><td>下一趟车</td><td>{$next['start_time']}</td><td>{$next['arrive_time']}</td><td>{$time}</td><td>出发地:{$next['start_station_name']}</td><td>目的地:{$next['end_station_name']}</td><td>{$next['type']}</td><td>{$next['price']}</td></tr>";
            $show .= "<tr></tr>";
            $time = (string)((int)($price['time']))."时".(string)(($price['time'] - (int)($price['time'])) * 60)."分";
            $show .= "<tr><td>价格最便宜</td><td>{$price['start_time']}</td><td>{$price['arrive_time']}</td><td>{$time}</td><td>出发地:{$price['start_station_name']}</td><td>目的地:{$price['end_station_name']}</td><td>{$price['type']}</td><td>{$price['price']}</td></tr>";
            $show .= "</table>";
            return $show;
        }
  
    }
?>