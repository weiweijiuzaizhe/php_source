<?php



class Autohome{
    /*
	 * 得到汽车之家的车系和指定的url
	 * @param $total_string http://car.autohome.com.cn/zhaoche/pinpai/
	 * @return  汽车之家的具体车系的url数组，$matched_html[0] 匹配到的html段，$matched_html[1]车系id,
	 * $matched_html[2]车系名称,$matched_html["url"]车系的url,$matched_html["info"]是否在售的消息on_sale在售，unavaiable停售或未发布
	 * 
	 */
	function get_autohome_series($total_string){
		
		//<div class="grade_js_top10"><a href="http://www.autohome.com.cn/692/" style="text-decoration:none;" target="_blank" title="奥迪A4L">奥迪A4L</a></div> 在售的车
		//<div class="grade_js_top11" ><a href="http://www.autohome.com.cn/509/" style="text-decoration:none;" target="_blank" title="奥迪A6">奥迪A6</a></div> 停产或未售的车

		$to_return_array = array();
		$current_key = 0;
		
		$onsale_pattern = "|<div class=\"grade_js_top10\">.*<a href=\"http://www.autohome.com.cn/(.*)/\" style=\"text-decoration:none;\" target=\"_blank\" title=\"(.*)\">.*</a></div>|isU";

		preg_match_all($onsale_pattern, $total_string, $matched_html);

		
		foreach($matched_html[ 0 ] as $key=>$value){
			$to_return_array["info"][ $current_key ] = "on_sale";
			$to_return_array["url"][ $current_key ] = "http://www.autohome.com.cn/".$matched_html[ 1 ][ $key ]."/";
			$to_return_array[ 0 ][ $current_key ] = $matched_html[ 0 ][ $key ];
			$to_return_array[ 1 ][ $current_key ] = $matched_html[ 1 ][ $key ];
			$to_return_array[ 2 ][ $current_key ] = $matched_html[ 2 ][ $key ];
			$current_key++;
		}
		
		
		
	    $unsale_pattern = "|<div class=\"grade_js_top11\">.*<a href=\"http://www.autohome.com.cn/(.*)/\" style=\"text-decoration:none;\" target=\"_blank\" title=\"(.*)\">.*</a></div>|isU";
		
		preg_match_all($unsale_pattern, $total_string, $matched_html);

		
		foreach($matched_html[ 0 ] as $key=>$value){
			$to_return_array["info"][ $current_key ] = "unavaiable";
			$to_return_array["url"][ $current_key ] = "http://www.autohome.com.cn/".$matched_html[ 1 ][ $key ]."/";
			$to_return_array[ 0 ][ $current_key ] = $matched_html[ 0 ][ $key ];
			$to_return_array[ 1 ][ $current_key ] = $matched_html[ 1 ][ $key ];
			$to_return_array[ 2 ][ $current_key ] = $matched_html[ 2 ][ $key ];
			$current_key++;
		}
		
		return $to_return_array;
		
		
	
	}
	
	
	/*
	 * 对于汽车之家的灰色车系，点进去有可能是未上市或停售，停售的在车系名称后有"停售"两个字,这个函数得到这个车系的所有停售车款，如果没有停售就返回-1
	 * @param $total_string 类似http://www.autohome.com.cn/484/的内容
	 * @return  pcauto的具体车型的url数组,$matched_url[ 0 ]匹配到的html段，$matched_html[ 1 ]车型id，$matched_html[ 2 ]车型名称，$matched_html[ "url" ],没有找到返回-1
	 */
	public function get_autohome_grey_stop_sale($total_string){
		
		if(strpos( $total_string,"停售") == false){//没有找到停售两个字
			return -1;
		}
		
		
		//<td class='name_d'><a title='2008款 1.0L 手动标准型' href='spec/4290/'>2008款 1.0L 手动标准型</a></td>
		$pattern = "|<a title=.*href='spec/(.*)/'>(.*)</a>|";
		preg_match_all($pattern, $total_string, $matched_html);
	    foreach($matched_html[ 0 ] as $key => $value){
			$matched_html[ "url" ][ $key ] = "http://www.autohome.com.cn/spec/".$matched_html[ 1 ][ $key ] ."/";
			$matched_html[ "info" ][ $key ] = 'stop_sale';
		}
		
		return $matched_html;
	}
	
	
	/*
	 * 对于汽车之家蓝色的车系，点击去就显示的是全部车款，分成在产车款和停售车款，这个函数得到在售车款
	 * @param $total_string 类似http://www.autohome.com.cn/650/的内容
	 * @return  pcauto的具体车型的url数组,$matched_url[ 0 ]匹配到的html段，$matched_html[ 1 ]车型id，$matched_html[ 2 ]车型名称，$matched_html[ "url" ]
	 */
	public function get_autohome_onsale($total_string){
		//<p><a href='spec/11069/' title='2012款 1.4 TFSI Urban'>2012款 1.4 TFSI Urban</a></p>
		
		
		$pattern = "|<p><a href='spec/(.*)/'.*title=.*>(.*)</a></p>|isU";

		preg_match_all($pattern, $total_string, $matched_html);

		foreach($matched_html[ 0 ] as $key => $value){
			$matched_html[ "url" ][ $key ] = "http://www.autohome.com.cn/spec/".$matched_html[ 1 ][ $key ] ."/";
			$matched_html[ "info" ][ $key ] = 'on_sale';
		}
		
		return $matched_html;
	}
	
    /*
	 * 对于汽车之家蓝色的车系，点击去就显示的是全部车款，分成在产车款和停售车款，这个函数得到停售车款，这里面有ajax的模拟
	 * @param $total_string 类似http://www.autohome.com.cn/650/的内容
	 * @return  pcauto的具体车型的url数组,$matched_url[ 0 ]匹配到的html段，$matched_html[ 1 ]车型id，$matched_html[ 2 ]车型名称，$matched_html[ "url" ],["info"]在售与否的信息
	 */
	public function get_autohome_stop_sale($total_string){
		//ajax的链接
		//<a target='_self' href='javascript:void(0)' onclick='GetStopSpec(2486)'>2011款</a>
		
		
		$pattern = "|onclick='GetStopSpec\((.*)\)'>.*款</a>|isU";

		preg_match_all($pattern, $total_string, $matched_html);//得到ajax的连接

		$current_key = 0;
		$array_to_return = array();
		foreach($matched_html[ 1 ] as $key => $value){
			$ajax_url = "http://www.autohome.com.cn/ashx/series_allSpec.ashx?seriesId=692&yearId=".$value."&flag=false";
			
			$ajax_content = $this->url_fetch($ajax_url);//进行ajax请求得到ajax内容
			
			
			
			$ajax_content = iconv('gbk','utf-8',$ajax_content);
			$ajax_to_return = $this->get_autohome_ajax_parse($ajax_content);
			
			
			foreach($ajax_to_return[ 1 ] as $key => $value){
				$array_to_return[ 0 ][ $current_key ] = $ajax_to_return[ 0 ][ $key ];
				$array_to_return[ 1 ][ $current_key ] = $ajax_to_return[ 1 ][ $key ];
				$array_to_return[ 2 ][ $current_key ] = $ajax_to_return[ 2 ][ $key ];
				$array_to_return[ "url" ][ $current_key ] = $ajax_to_return[ "url" ][ $key ];
				$array_to_return[ "info" ][ $current_key ] = $ajax_to_return[ "info" ][ $key ];
				$current_key++;

			}
			
		}
		
		
		return $array_to_return;
	}
	
	/*
	 * 对于汽车之家蓝色的车系，点击去就显示的是全部车款，分成在产车款和停售车款，这个函数为了得到停售车款，得到ajax的返回内容之后要解析出车型和这个车型的url
	 * @param $total_string 类似http://www.autohome.com.cn/650/的内容
	 * @return  pcauto的具体车型的url数组,$matched_url[ 0 ]匹配到的html段，$matched_html[ 1 ]车型id，$matched_html[ 2 ]车型名称，$matched_html[ "url" ]
	 */
	public function get_autohome_ajax_parse($ajax_content){
		
		//<a href='spec/5863/' title='2010款 1.8 TFSI 舒适型'>2010款 1.8 TFSI 舒适型</a>
		$pattern = "|<a href='spec/(.*)/'.*title='.*'>(.*)</a>.*实拍图片.*参数配置|isU";
		
		preg_match_all($pattern, $ajax_content, $matched_html);
		
	    foreach($matched_html[ 0 ] as $key => $value){
			$matched_html[ "url" ][ $key ] = "http://www.autohome.com.cn/spec/".$matched_html[ 1 ][ $key ] ."/";
			$matched_html[ "info" ][ $key ] = 'stop_sale';
		}
		
		return $matched_html;
	}
	
	
    //得到页面内容的爬虫
	private  function  url_fetch($url){
		
		echo("fetching $url\n");
		
		$sleep_duration = rand(5, 10);//休息一下，以免被封IP
		sleep($sleep_duration);
		
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		return $output;
	}
}

   

?>