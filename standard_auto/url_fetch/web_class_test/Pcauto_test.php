<?php

require_once('../web_class/Pcauto.php');

//实际上是来自于:http://price.pcauto.com.cn/sg919/的内容，下面的处理是得到在售车型的url
	//$fp = fopen("sg5013.htm","r");


	$fp = fopen("../web_page/3182.htm","r");
	$total_string = "";
	while($temp_sting = fgets($fp)){
		$total_string = $total_string.$temp_sting;
	}
	
	$total_string = iconv('gbk','utf-8',$total_string);
	
	
	//var_dump(get_pcauto_stop_produce($total_string));
	
	$pcauto = new Pcauto();
	
	
	var_dump($pcauto->get_pcauto_onsale($total_string));
    //var_dump($pcauto->get_pcauto_stop_produce($total_string));

	
	
	
?>