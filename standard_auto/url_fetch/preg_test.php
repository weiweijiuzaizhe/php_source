<?php
require_once('Pcauto.php');

//实际上是来自于:http://price.pcauto.com.cn/sg919/的内容，下面的处理是得到在售车型的url
	//$fp = fopen("sg5013.htm","r");


	$fp = fopen("web_page/3182.htm","r");
	$total_string = "";
	while($temp_sting = fgets($fp)){
		$total_string = $total_string.$temp_sting;
	}
	
	$total_string = iconv('gbk','utf-8',$total_string);
	
	
	//var_dump(get_pcauto_stop_produce($total_string));
	
	$pcauto = new Pcauto();
	
	
	//var_dump($pcauto->get_pcauto_onsale($total_string));
    var_dump($pcauto->get_pcauto_stop_produce($total_string));

	/*
	 * 
	 * 得到类似http://price.pcauto.com.cn/sg919/上在售车型的url
	 * @param $total_string某一车系的页面内容
	 * @return  pcauto的具体车型的url数组
	 * 
	 */
	function get_pcauto_onsale($total_string){

		$pattern = "|<span class=\"sCol5\">详情</span>(.*)<span class=\"sCol5\">详情</span>|isU";

		preg_match_all($pattern, $total_string, $matched_html);

		$html_wanted = $matched_html[ 1 ][ 0 ];

		//echo("$total_string\n");
		//$pattern_url = "|<em><a href=\"(.{0,30})\"target=\"_blank\" title=\"|isU";
		$pattern_url = "|<em><a href=\"(.*)\" target=\"_blank\"|isU";

		preg_match_all($pattern_url, $html_wanted, $matched_url);

		return	$matched_url[ 1 ] ;
	}


	/*
	 * 得到类似http://price.pcauto.com.cn/sg919/上停产汽车的url
	 * @param $total_string某一车系的页面内容
	 * @return  pcauto的具体车型的url数组
	 * 
	 */
	function get_pcauto_stop_produce($total_string){

		$pattern = "|<span class=\"sCol5\">详情</span>(.*)<script>|isU";

		preg_match_all($pattern, $total_string, $matched_html);

		$html_wanted = $matched_html[ 1 ][ 0 ];

		$pattern_url = "|<em><a href=\"(.*)\" target=\"_blank\"|isU";

		preg_match_all($pattern_url, $html_wanted, $matched_url);

		return	$matched_url[ 1 ] ;
		
		
		
	}
	
	
	/*
	 * 得到pcauto的车系和指定的url
	 * @param $total_string某类车型的页面内容（如中型车price.pcauto.com.cn/sg3564/，小型车http://price.pcauto.com.cn/cars/c110/）
	 * @return  pcauto的具体车系的url数组，$matched_html[0] 匹配到的html段，$matched_html[1]车系的url,$matched_html[2]车系名称
	 * 
	 */
	function get_pcauto_single_brand($total_string){
		
		$pattern = "|http://price.pcauto.com.cn/sg(.{1,10})/\">(.{1,20})</a>|isU";

		preg_match_all($pattern, $total_string, $matched_html);

		//还原回要找的url
		foreach($matched_html[ 1 ] as $key =>$value){
			$matched_html[ 1 ][ $key ] = "http://price.pcauto.com.cn/sg".$value."/";
		}
		
		
		return	$matched_html ;
		
	}
	
	
?>