<?php


class  Wangyiauto{
     
     
    /*
	 * 得到网易的车系和指定的url
	 * @param $total_string http://product.auto.163.com/firstchar/
	 * @return  网易的具体车系的url数组，$matched_html[0] 匹配到的html段，$matched_html[1]车系id,$matched_html[2]车系名称,$matched_html["url"]车系的url,$matched_html["info"]停产的消息
	 * 
	 */
	function get_wangyiauto_series($total_string){
		
		$pattern = "|<a href=\"/series/(.*)\.html.*\" title=\".*\"><span>(.*)</span>.*</a>|isU";

		preg_match_all($pattern, $total_string, $matched_html);
		 
		foreach($matched_html[ 0 ] as $key=>$value){
			if(strpos($value, "停产")){
				$matched_html['info'][ $key ] = "stop produce";
			}
			$matched_html["url"][ $key ] = "http://product.auto.163.com/series/".$matched_html[ 1 ][ $key ].".html#CX003";
		}
		return $matched_html;
	
	}
	
	
	/*
	 * 对于网易已经标记为停产的车系，点击去就显示的是全部车款
	 * @param $total_string
	 * @return  pcauto的具体车型的url数组,$matched_url[ 0 ]匹配到的html段，$matched_html[ 1 ]车型id，$matched_html[ 2 ]车型名称，$matched_html[ "url" ]
	 */
	function get_wangyiauto_stop_series( $total_string){
		//  <td><span><a href="/product/0000GZKB.html"  title="雪铁龙毕加索 2004款 2.0AT 天窗版">2004款 2.0AT 天窗版<span style="color:#999;">(停产)</span></a></span> </td> 
		
		$pattern = "|<span><a href=\"/product/(.*)\.html\".*title=\"(.*)\">.*<span style=.*</span></a></span>|isU";

		preg_match_all($pattern, $total_string, $matched_html);
		
		foreach($matched_html[ 1 ] as $key => $value){
			$matched_html[ "url" ][ $key ] = "http://product.auto.163.com/product/".$matched_html[ 1 ][ $key ] .".html";
		}
		return $matched_html;
	}
	
	/*
	 * 对于网易没有标记为停产的车系，点击去就显示的是全部车款，分成在产车款和停售车款，这个函数得到在产车款
	 * @param $total_string 类似http://product.auto.163.com/series/2370.html#CX003的内容
	 * @return  pcauto的具体车型的url数组,$matched_url[ 0 ]匹配到的html段，$matched_html[ 1 ]车型id，$matched_html[ 2 ]车型名称，$matched_html[ "url" ]
	 */
	function get_wangyiauto_onsale($total_string){
		

		$pattern = "|<th>车款热度</th>.*<th>添加对比</th>(.*)<th>车款热度</th>.*<th>添加对比</th>|isU";

		preg_match_all($pattern, $total_string, $matched_html);

		$total_string = $matched_html[1][0];
		
		//<td><p class="title"><span><a href="/product/000BBAJH.html"  title="大众POLO 2011款 1.4MT致乐版">2011款 1.4MT致乐版</a></span>
		$pattern = "|<span><a href=\"/product/(.*)\.html\".*title=\"(.*)\">.*</a></span>|isU";

		preg_match_all($pattern, $total_string, $matched_html);
		
		foreach($matched_html[ 1 ] as $key => $value){
			$matched_html[ "url" ][ $key ] = "http://product.auto.163.com/product/".$matched_html[ 1 ][ $key ] .".html";
		}
		return $matched_html;
		

		}
	
    /*
	 * 对于网易没有标记为停产的车系，点击去就显示的是全部车款，分成在产车款和停售车款，这个函数得到停售车款
	 * @param $total_string 类似http://product.auto.163.com/series/2370.html#CX003的内容
	 * @return  pcauto的具体车型的url数组,$matched_url[ 0 ]匹配到的html段，$matched_html[ 1 ]车型id，$matched_html[ 2 ]车型名称，$matched_html[ "url" ]
	 */
	function get_wangyiauto_stop_produce($total_string){
		

		$pattern = "|<th>车款热度</th>.*<th>添加对比</th>.*<th>车款热度</th>.*<th>添加对比</th>(.*)实拍图片|isU";

		preg_match_all($pattern, $total_string, $matched_html);

		$total_string = $matched_html[1][0];
		
		//<td><p class="title"><span><a href="/product/000BBAJH.html"  title="大众POLO 2011款 1.4MT致乐版">2011款 1.4MT致乐版</a></span>
		$pattern = "|<span><a href=\"/product/(.*)\.html\".*title=\"(.*)\">.*</a></span>|isU";

		preg_match_all($pattern, $total_string, $matched_html);
		
		foreach($matched_html[ 1 ] as $key => $value){
			$matched_html[ "url" ][ $key ] = "http://product.auto.163.com/product/".$matched_html[ 1 ][ $key ] .".html";
		}
		return $matched_html;
		

		}
	
	
}

?>