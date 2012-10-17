<?php


class Pcauto{
	/*
	 *
	 * 得到http://price.pcauto.com.cn/sg919/上在售汽车的url
	 * @param $total_string某一车型的页面内容
	 * @return  pcauto的具体车型的url数组,$matched_url[ 0 ]匹配到的html段，$matched_html[ 1 ]车型id，$matched_html[ 2 ]车型名称，$matched_html[ "url" ]
	 */
	function get_pcauto_onsale($total_string){

		$pattern = "|<div class=\"contentdiv\">.{1,4}<div class=\"bar\">(.*)<div class=\"contentdiv\">.{1,4}<div class=\"bar\">|isU";

		//$pattern = "|<div class=\"contentdiv\">(.*)<div class=\"contentdiv\">|isU";

		preg_match_all($pattern, $total_string, $matched_html);

		//var_dump($matched_html[0][0]);

		$html_wanted = $matched_html[0][0];

		//echo("$total_string\n");
		//$pattern_url = "|<em><a href=\"(.{0,30})\"target=\"_blank\" title=\"|isU";
		$pattern_url = "|<em><a href=\"http://price.pcauto.com.cn/m(.*){1,10}/\" target=\"_blank\" title=\"(.*){1,20}\">|isU";

		preg_match_all($pattern_url, $html_wanted, $matched_url);


		foreach($matched_url[ 1 ] as $key => $value){
			$matched_url[ "url" ][ $key ] =  "http://price.pcauto.com.cn/m".$value."/";
				
		}

		return	$matched_url ;
	}


	/*
	 * 得到http://price.pcauto.com.cn/sg919/上停产汽车的url
	 * @param $total_string某一车系的页面内容
	 * @return  pcauto的具体车型的url数组,$matched_url[ 0 ]匹配到的html段，$matched_html[ 1 ]车型id，$matched_html[ 2 ]车型名称，$matched_html[ "url" ]
	 *
	 */
	function get_pcauto_stop_produce($total_string){

		$pattern = "|<div class=\"contentdiv\">\s*<div class=\"bar\">.*<div class=\"contentdiv\">\s*<div class=\"bar\">(.*)<script>|isU";

		preg_match_all($pattern, $total_string, $matched_html);

		//var_dump($matched_html[2]);

		$html_wanted = $matched_html[1][0];

		$pattern_url = "|<em><a href=\"http://price.pcauto.com.cn/m(.*){1,10}/\" target=\"_blank\" title=\"(.*){1,20}\">|isU";

		preg_match_all($pattern_url, $html_wanted, $matched_url);


		foreach($matched_url[ 1 ] as $key => $value){
			$matched_url[ "url" ][ $key ] =  "http://price.pcauto.com.cn/m".$value."/";
				
		}

		return	$matched_url ;



	}


	/*
	 * 得到pcauto的车系和指定的url
	 * @param $total_string某类车型的页面内容（如中型车price.pcauto.com.cn/sg3564/，小型车http://price.pcauto.com.cn/cars/c110/）
	 * @return  pcauto的具体车型的url数组，$matched_html[0] 匹配到的html段，$matched_html[1]车系id,$matched_html[2]车系名称,$matched_html["url"]车系的url
	 *
	 */
	function get_pcauto_series($total_string){

		$pattern = "|http://price.pcauto.com.cn/sg(.{1,10})/\">(.{1,20})</a>|isU";

		preg_match_all($pattern, $total_string, $matched_html);

		//还原回要找的url
		foreach($matched_html[ 1 ] as $key =>$value){
			$matched_html[ "url" ][ $key ] = "http://price.pcauto.com.cn/sg".$value."/";
		}


		return	$matched_html ;

	}
	
	
	
	/*
	 * 得到pcauto的车系和指定的url
	 * @param $total_string某类车型的页面内容(如中型车price.pcauto.com.cn/sg3564/，小型车http://price.pcauto.com.cn/cars/c110/)
	 * @return  pcauto的具体车型的url数组，$matched_html[0] 匹配到的html段，$matched_html[1]车系id,$matched_html[2]车系名称,$matched_html["url"]车系的url
	 */
	function get_on_sale_series($total_string){
		$pattern = "|http://price.pcauto.com.cn/sg(.{1,10})/\">(.{1,20})</a>|isU";

		preg_match_all($pattern, $total_string, $matched_html);

		//还原回要找的url
		foreach($matched_html[ 1 ] as $key =>$value){
			$matched_html[ "url" ][ $key ] = "http://price.pcauto.com.cn/sg".$value."/";
		}


		return	$matched_html ;
		
		
	}
}

?>