<?php
namespace app\View;
class trains{
		function show_trains($datas){
			\lib\core\view::display('detailsHtml/details',$datas);
		}
}
?>