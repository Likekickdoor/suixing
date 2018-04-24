<?php

/**
*@param OpenURL类，输入等待爬取URL数组，变可以把结果以‘数组’形式返回，
其内容可能是json形式的，也可以是其他的，如同file_get_contents()
*/
class OpenURLClass
{
	private $urls;
	private $proxy;
	function __construct($scanf_urls=[],$Is_proxy=false)
	{
		$this->urls=$scanf_urls;
		$this->Is_proxy=$Is_proxy;
	}
/**
*@param  collect()打开批URL，进行访问
*/
   function collect(){
   	$useragent=[
             'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E; LBBROWSER)',
             'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; SV1; QQDownload 732; .NET4.0C; .NET4.0E; SE 2.X MetaSr 1.0)',
             'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/534.57.2 (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2',
             'Opera/8.0 (Windows NT 5.1; U; en)',
             'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; en) Opera 9.50',
             'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36',
             'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:47.0) Gecko/20100101 Firefox/47.0'
          ];
    $mh=curl_multi_init();//创建批处理cURL句柄
    $urls=$this->urls;
    foreach($urls as $i =>$url){    	
    $conn[$i] = curl_init();
    curl_setopt($conn[$i], CURLOPT_URL, $url);
    curl_setopt($conn[$i], CURLOPT_USERAGENT, $useragent[mt_rand(0,5)]);//用户代理
    curl_setopt($conn[$i], CURLOPT_HEADER ,0);//启用1时会将头文件的信息作为输出
	  curl_setopt($conn[$i], CURLOPT_TIMEOUT, 60);//传递数据等待时间
    curl_setopt($conn[$i], CURLOPT_CONNECTTIMEOUT,60); //在尝试连接时等待的秒数。设置为0，则无限等待
    curl_setopt($conn[$i],CURLOPT_RETURNTRANSFER,true); // 不将爬取代码写到浏览器，而是转化为字符串//TRUE 将curl_exec()获取的信息以字符串返回，而不是直接输出。
    //若给定url自动跳转到新的url,有了下面参数可自动获取新url内容：302跳转
    curl_setopt($conn[$i], CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($conn[$i], CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:61.135.169.125', 'CLIENT-IP:61.135.169.125'));  //构造IP
    curl_setopt($conn[$i], CURLOPT_REFERER, "http://www.baidu.com/");//构造来路
    // curl_setopt($conn[$i], CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($conn[$i], CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($conn[$i], CURLOPT_SSL_VERIFYPEER, false); //FALSE 禁止 cURL 验证对等证书
    curl_setopt($conn[$i], CURLOPT_SSL_VERIFYHOST, false); //FALSE 不检查证书
    curl_multi_add_handle ($mh,$conn[$i]);//向curl批处理会话中添加单独的curl资源
    if($this->proxy===true){
    curl_setopt($conn[$i], CURLOPT_HTTPPROXYTUNNEL, 1);  
    curl_setopt($conn[$i], CURLOPT_PROXY,'113.99.218.102:9797');
    }

    }
    $active=null;
    do {//执行上方curl指令
    curl_multi_exec($mh,$active);
    } while ($active>0);
    
    $json_strs=[];//定义一个数组装下json_str或者抓取的数据组
    for($i=0;$i<count($urls);$i++) {
    $json_str=curl_multi_getcontent($conn[$i]);
    array_push($json_strs, $json_str);
    $code=curl_getinfo($conn[$i],CURLINFO_HTTP_CODE);//得到http头状态码,判断是否被封
    printf("%d Link static: %d\r\n",$i,$code);//输出每个的状态码
        // if($code!=200)
        // {
        //   exit();
        // }
    }
    
    foreach ($urls as $i => $url){
    curl_multi_remove_handle($mh,$conn[$i]);//移除curl批处理句柄资源中的某个句柄资源
    curl_close($conn[$i]);
    }
    curl_multi_close($mh);//关闭一组cURL句柄
    return $json_strs;//返回打开的 批URL网页内容数组
 }


/**
*@param curl_get_file()单线程打开网页
*/
   function curl_get_file($url){
   $useragent=[
             'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E; LBBROWSER)',
             'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; SV1; QQDownload 732; .NET4.0C; .NET4.0E; SE 2.X MetaSr 1.0)',
             'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/534.57.2 (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2',
             'Opera/8.0 (Windows NT 5.1; U; en)',
             'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; en) Opera 9.50',
             'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36',
             'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:47.0) Gecko/20100101 Firefox/47.0'
          ];
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_HEADER, 0);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   //若给定url自动跳转到新的url,有了下面参数可自动获取新url内容：302跳转
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   //设置cURL允许执行的最长秒数。
   curl_setopt($ch, CURLOPT_TIMEOUT, 60);
   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,60); //在尝试连接时等待的秒数。设置为0，则无限等待
   curl_setopt($ch, CURLOPT_USERAGENT, $useragent[mt_rand(0,5)]);
   curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:61.135.169.125', 'CLIENT-IP:61.135.169.125'));  //构造IP
   curl_setopt($ch, CURLOPT_REFERER, "http://www.baidu.com/");//构造来路
   curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
   //curl_setopt($ch, CURLOPT_VERBOSE,true);//报告意外的事
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //FALSE 禁止 cURL 验证对等证书
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //FALSE 不检查证书 
   $json_str=curl_exec($ch);
   return $json_str;
   }
}

?>





