<?php
    namespace app\View;
    use \lib\core\view;
    class bus{

        private $show = '';

        function show_bus($file,$data){
            $datas = explode(';',$data);
            $lists = explode(',',$datas[0]);
            if(count($lists) < 7){
                $this->show .= "<center><h2>暂未查询到汽车票数据</h2></center>";
                return FALSE;
            }
            $this->show .= "<table class='bus'>";
            $this->show .= "<tr><th>出发时间</th><th>到达时间</th><th>时长</th><th>出发地</th><th>目的地</th><th>车型</th><th>价格</th></tr>";
            foreach($datas as $data){
                if($data == [''])
                    continue;
                $lists = explode(',',$data);
                $this->show .= "<tr>";
                foreach($lists as $list){
                    if($list == '')
                        continue;
                    $this->show .= "<td>$list</td>";
                }
                $this->show .= "</tr>";
            }
            $this->show .= "</table>";
            view::display($file,$this->show);
        }

        function error($err){
            $this->show = "<script>alert('{$err}');</script>";
        }
    }
?>