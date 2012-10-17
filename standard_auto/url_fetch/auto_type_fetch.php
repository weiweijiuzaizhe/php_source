<?php
require_once('Pcauto.php');

class autoTypeFetch{

	//得到页面内容的爬虫
	public  function  url_fetch($url){
		
		echo("fetching $url\n");
		
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


	public function get_qq_107(){

		$siteid = 107;
		$url1="http://data.auto.qq.com/car_public/1/index_type.shtml"; //qq汽车的首页

		$page_content = $this->url_fetch($url1);


		$output_type = iconv('gbk','utf-8',$page_content);//得到页面内容并转码

		/*
		 *  $matches[0] 为全部模式匹配的数组，$matches[1] 为第一个括号中的子模式所匹配的字符串组成的数组
		 */
		
		$pattern_idfregment = '|<em><a name=\"(.{0,10})\">(.{0,30})</a></em>(.*)</ul>|isU';
		preg_match_all( $pattern_idfregment , $output_type , $matchesidfreg );
		$pattern_ins = '|<a href=\"http://data.auto.qq.com/car_serial/(.{0,10})/index.shtml\" target=\"_blank\" >(.{0,30})</a>|isU'; // id name
		for( $p = 0 ; $p < count($matchesidfreg[ 1 ]); $p++ ){
			$cartype = $matchesidfreg[ 2 ][ $p ];
			$idfregment = $matchesidfreg[ 3 ][ $p ];
			preg_match_all($pattern_ins, $idfregment, $matchesins);
			for($m = 0;$m < count($matchesins[ 1 ]); $m++){
				$id = $matchesins[ 1 ][ $m ];
				$name = $matchesins[ 2 ][ $m ];

				$url2 = "http://data.auto.qq.com/car_serial/".$id."/modelscompare.shtml";//得到具体某一款车的车型页


				$sleep_duration = rand(5, 10);//休息一下，以免被封IP
				sleep($sleep_duration);

				$page_content = $this->url_fetch($url2);
				$output_canshu = iconv('gbk','utf-8',$page_content);//得到页面内容并转码

				$pattern_brand = '|<a href=\"http://data.auto.qq.com/car_brand/(.{0,10})/index.shtml\" target=\"_blank\" title=\"(.{0,50})\">(.{0,50})</a>|isU';
				preg_match_all($pattern_brand, $output_canshu, $matchesbrand);

				$brand = $matchesbrand[ 3 ][ 0 ];//这里李珏可能写错了，怎么两个是一样的呢？
				$producer = $matchesbrand[ 3 ][ 0 ];

				$pattern_fregment = '|<ul><li class=\"bar\"></li>(.*)</li></ul>|isU';
				$pattern_s = '|<li(.*)</li>|isU';
				preg_match_all($pattern_fregment, $output_canshu, $matchesfreg);
				$price = "";
				$fuel = "";
				$ckg = "";
				$trans ="";
				$door = "";
				$enginesize = "";
				$year = "";
				$style = "";
				$maket = "";
				$AutoModeID="";
				$salestatus = "";
				$market = "";


				for( $q= 0 ;$q < count($matchesfreg[ 1 ]) ; $q++ ){
					$freg = $matchesfreg[ 1 ][ $q ];
					preg_match_all($pattern_s, $freg, $matchess);
					$maketype = preg_replace('/>/','',$matchess[ 1 ][ 5 ]);

					if($maketype=="国产"){
						$maketype = "自主";
					}
					else{
						$maketype = $maketype;
					}
					$price = $price.preg_replace('/>/','',$matchess[ 1 ][ 1 ])."万,";
					$fuel = $fuel.preg_replace('/>/','',$matchess[ 1 ][ 2 ]).",";
					$ckg = $ckg.preg_replace('/>/','',$matchess[ 1 ][ 6 ]).",";
					$trans = $trans.preg_replace('/>/','',$matchess[ 1 ][ 10 ]).",";
					$door = $door.preg_replace('/>/','',$matchess[ 1 ][ 17 ])."门".preg_replace('/>/','',$matchess[ 1 ][ 15 ])."座".",";
					$enginesize = $enginesize.preg_replace('/>/','',$matchess[ 1 ][ 24 ])."ml,";

				}
				
				$tmpa=$siteid."\t".$id."\t".$producer."\t".$producer."\t".$brand."\t".$name."\t".$year."\t".$cartype."\t".$style."\t".$maketype."\t".$maket."\t".$salestatus."\t";
				$tmpb=$price."\t".$enginesize."\t".$fuel."\t".$trans."\t".$door."\t".$ckg;
				$result = $tmpa.$tmpb."\n";
				echo $result;

			}

		}
	
	}

	public function get_pcauto_102(){
		
		$pcauto = new Pcauto();
		
		$url1 = "http://price.pcauto.com.cn/cars/";
		$arr = array("c76","c110","c73","c72","c71",
		"c70","c74","c75","c111","c112");
		
		foreach ($arr as $type){
			
			$url_type = $url1.$type."/";
			
			$sleep_duration = rand(5, 10);//休息一下，以免被封IP
			sleep($sleep_duration);
			
			$total_string = iconv('gbk','utf-8',$this->url_fetch($url_type));

			$brand_auto_array = $pcauto->get_pcauto_series($total_string);
			
			foreach($brand_auto_array[ 1 ] as $key => $value){
				$auto_series_id = $brand_auto_array[ 1 ][ $key ];//网站的车系id
				$auto_series_name = $brand_auto_array[ 2 ][ $key ];//车系名称
				$auto_series_url = $brand_auto_array[ "url" ][ $key ];//车系的url
				
				$sleep_duration = rand(5, 10);//休息一下，以免被封IP
				sleep($sleep_duration);
				
				$total_string = iconv('gbk','utf-8',$this->url_fetch($auto_series_url));//得到某一个特定车系的页面
				
				$on_sale_array = $pcauto->get_pcauto_onsale($total_string);
				foreach($on_sale_array[ 1 ] as $key => $value){
					$auto_type_name = $on_sale_array[ 2 ][ $key ];//网站车型名称
					$auto_type_id = $on_sale_array[ 1 ][ $key ];//网站车型ID
					$auto_type_url = $on_sale_array[ "url" ][ $key ];//车型页面
					$config_url = "http://price.pcauto.com.cn/m".$auto_type_id."/config.html";
					
					echo("on_sale\t$type\t$auto_series_id\t$auto_series_name\t$auto_type_id\t$auto_type_name\t$auto_type_url\t$config_url\n");
				}
				
				
				$stop_produce_array = $pcauto->get_pcauto_stop_produce($total_string);
				foreach($stop_produce_array[ 1 ] as $key => $value){
					$auto_type_name = $stop_produce_array[ 2 ][ $key ];//网站车型名称
					$auto_type_id = $stop_produce_array[ 1 ][ $key ];//网站车型ID
					$auto_type_url = $stop_produce_array[ "url" ][ $key ];//车型页面
					$config_url = "http://price.pcauto.com.cn/m".$auto_type_id."/config.html";
					
					echo("stop producer\t$type\t$auto_series_id\t$auto_series_name\t$auto_type_id\t$auto_type_name\t$auto_type_url\t$config_url\n");
				}
				
				
				
			
				
			}
			
			}	
			
			/*
			$pattern_ins = '|<a title=\"(.{0,50})\" target=\"_blank\" href=\"http://price.pcauto.com.cn/serial.jsp\\?sid=(.{0,10})\">|isU';//
			preg_match_all($pattern_ins, $output_type, $matchesins);
			
			
			for( $p = 0; $p < count( $matchesins[ 1 ] ); $p++ ){
				$id = $matchesins[ 2 ][ $p ];
				$name = $matchesins[ 1 ][ $p ];
				$url2 = "http://price.pcauto.com.cn/serial.jsp?sid=".$id;
				
				
				
				$sleep_duration = rand(5, 10);//休息一下，以免被封IP
				sleep($sleep_duration);
				
				$output_home = iconv('gbk','utf-8',$this->url_fetch($url2));
				$pattern_brand = '|品牌：<a href=\"http://price.pcauto.com.cn/new_brand.jsp\\?bid=(.{0,10})\" target=\"_blank\" title=\"(.{0,50})\">|isU';
				$pattern_type = '|级别：<a href=\"http://price.pcauto.com.cn/cars/(.{0,10})/\" target=\"_blank\" title=\"(.{0,30})\">|isU';

				preg_match_all($pattern_brand, $output_home, $matchesbrand);
				$brand = $matchesbrand[ 2 ][ 0 ];
				preg_match_all($pattern_type, $output_home, $matchestype);
				$cartype = $matchestype[ 2 ][ 0 ]; 
     
				$url3 = "http://price.pcauto.com.cn/serial_config.jsp?sid=".$id;
				
				$sleep_duration = rand(5, 10);//休息一下，以免被封IP
				sleep($sleep_duration);
				
				$output_canshu = iconv('gbk','utf-8',$this->url_fetch($url3));
	  
				$pattern_fregment = '|<td width=\"142\" style=\"display: table-cell;\" data-year=(.*)</table>|isU';
				$pattern_price = '|<a href=\"http://price.pcauto.com.cn/(.{0,10})/price/\" target=\"_blank\">(.*)</a>|isU';
				$pattern_s = '|<nobr>(.*)</nobr>|isU';  //排量。长宽高。变速箱。燃油标号。动力类型
	 
				preg_match_all($pattern_fregment, $output_canshu, $matchesfreg);
				$price = "";
				$fuel = "";
				$ckg = "";
				$trans ="";
				$door = "";
				$enginesize = "";
				$year = "";
				$style = "";
				$maket = "";
				$AutoModeID="";
				$salestatus = ""; 
				$market = "";
				$SiteID = $siteid;
				$producer = "";
				$maketype = "";
				for( $q = 0; $q < count( $matchesfreg[ 1 ] ); $q++){
					$freg = $matchesfreg[ 1 ][ $q ];
					preg_match_all($pattern_s, $freg, $matchess);
					preg_match_all($pattern_price, $freg, $matchesprice);
					$price = $price.preg_replace('/--|\n/','',$matchesprice[ 2 ][ 0 ]).",";
					$fuel = $fuel.preg_replace('/--|\n/','',$matchess[ 1 ][ 24] ).preg_replace('/--|\n/','',$matchess[ 1 ][ 10 ]).",";
					$ckg = preg_replace('/--|\s|\n/','',preg_replace('/x/','*',$ckg.$matchess[ 1 ][ 7 ])).",";
					$trans = $trans.preg_replace('/--|\n/','',$matchess[ 1 ][ 20 ]).",";
					$door = $door.preg_replace('/--|\n/','',$matchess[ 1 ][ 52 ])."座".",";
					$enginesize = $enginesize.preg_replace('/^\\./','0.',preg_replace('/--|\n|\s/','',$matchess[ 1 ][ 2 ]))."L,";
				}
				
				$tmpa = $AutoModeID."\t".$SiteID."\t".$id."\t".$producer."\t".$producer."\t".$brand."\t".$name."\t".$year."\t".$cartype."\t".$style."\t".$maketype."\t".$maket."\t".$salestatus."\t";
				$tmpb = $price."\t".$enginesize."\t".$fuel."\t".$trans."\t".$door."\t".$ckg;
				$result = $tmpa.$tmpb."\n";
				echo $result;
			*/
				
		}
		
		
		public function get_wangyi_118(){

			$url1 = "http://product.auto.163.com/cartype/";
			$output_type = iconv('gbk','utf-8',$this->url_fetch($url1));
			
			$pattern_idfregment = '|<h5><span>(.{0,20})</span></h5> <span class=\"count\">\\(共<strong>(.{0,10})</strong>款\\)</span> <a class=\"return_top\" target=\"_self\" href=\"#top\">返回顶部</a></div>(.*)</div>|isU';
			preg_match_all($pattern_idfregment, $output_type, $matchesidfreg);
			$pattern_ins = '|<h5><a href=\"/series/(.{0,10}).html#0008F03\">(.{0,30})</a>(.{0,30})</h5>|isU'; // id name state

			for($p=0;$p<count($matchesidfreg[ 1 ]);$p++){
				
				echo("aaaaaaaaaaaaaaaaaaaa\n");
				$cartype = $matchesidfreg[ 1 ][ $p ];
				$idfregment = $matchesidfreg[ 3 ][ $p ];
				preg_match_all($pattern_ins, $idfregment, $matchesins);
				for($m=0;$m<count($matchesins[1]);$m++){
					$id = $matchesins[1][$m];
					$name = $matchesins[2][$m];
					$status = preg_replace('|\s|','',$matchesins[3][$m]);
					if($status=="[未上市]")$salestatus ="未上市";
					if($status=="")$salestatus ="在售";
					if($status=="[停产]")$salestatus ="停产";
					$url2 = "http://product.auto.163.com/series/config1/".$id.".html";
					
					
					
					$sleep_duration = rand(5, 10);//休息一下，以免被封IP
					sleep($sleep_duration);
					$output_canshu = iconv('gbk','utf-8',$this->url_fetch($url2));
					 
					$pattern_brand = '|<a href=\"/brand/(.{0,10}).html\" >(.{0,10})</a>|isU';
					preg_match_all($pattern_brand, $output_canshu, $matchesbrand);
					$brand=$matchesbrand[2][0];

					$pattern_s = '|<td class=\"set cell\"><span class=\"cell\">&nbsp;(.*)</span></td>|isU';

					$pattern_pricefreg = '|厂家指导价</span></td>(.*)</tr>|isU';
					preg_match_all($pattern_pricefreg, $output_canshu, $matchespricefreg);
					$pricefreg = $matchespricefreg[1][0];
					preg_match_all($pattern_s, $pricefreg, $matchesprice);

					$pattern_enginesizefreg = '|排气量\\(L\\)</span></td>(.*)</tr>|isU';
					preg_match_all($pattern_enginesizefreg, $output_canshu, $matchesenginesizefreg);
					$enginesizefreg = $matchesenginesizefreg[1][0];
					preg_match_all($pattern_s, $enginesizefreg, $matchesenginesize);

					$pattern_transfreg = '|挡位数</span></td>(.*)</tr>|isU';
					$pattern_transfreg1 = '|变速器形式</span></td>(.*)</tr>|isU';
					preg_match_all($pattern_transfreg, $output_canshu, $matchestransfreg);
					$transfreg = $matchestransfreg[1][0];
					preg_match_all($pattern_s, $transfreg, $matchestrans);
					preg_match_all($pattern_transfreg1, $output_canshu, $matchestransfreg1);
					$transfreg1 = $matchestransfreg1[1][0];
					preg_match_all($pattern_s, $transfreg1, $matchestrans1);

					$pattern_doorfreg = '|车门数\\(个\\)</span></td>(.*)</tr>|isU';
					preg_match_all($pattern_doorfreg, $output_canshu, $matchesdoorfreg);
					$doorfreg = $matchesdoorfreg[1][0];
					preg_match_all($pattern_s, $doorfreg, $matchesdoor);
					$pattern_seatfreg = '|座位数\\(个\\)</span></td>(.*)</tr>|isU';
					preg_match_all($pattern_doorfreg, $output_canshu, $matchesseatfreg);
					$seatfreg = $matchesseatfreg[1][0];
					preg_match_all($pattern_s, $seatfreg, $matchesseat);


					$pattern_cfreg = '|长\\(mm\\)</span></td>(.*)</tr>|isU';
					preg_match_all($pattern_cfreg, $output_canshu, $matchescfreg);
					$cfreg = $matchescfreg[1][0];
					preg_match_all($pattern_s, $cfreg, $matchesc);
					$pattern_kfreg = '|宽\\(mm\\)</span></td>(.*)</tr>|isU';
					preg_match_all($pattern_kfreg, $output_canshu, $matcheskfreg);
					$kfreg = $matcheskfreg[1][0];
					preg_match_all($pattern_s, $kfreg, $matchesk);
					$pattern_gfreg = '|高\\(mm\\)</span></td>(.*)</tr>|isU';
					preg_match_all($pattern_gfreg, $output_canshu, $matchesgfreg);
					$gfreg = $matchesgfreg[1][0];
					preg_match_all($pattern_s, $gfreg, $matchesg);


					$pattern_fuelfreg = '|燃油及标号</span></td>(.*)</tr>|isU';
					preg_match_all($pattern_fuelfreg, $output_canshu, $matchesfuelfreg);
					$fuelfreg = $matchesfuelfreg[1][0];
					preg_match_all($pattern_s, $fuelfreg, $matchesfuel);

					$price = "";
					$enginesize = "";
					$trans = "";
					$door = "";
					$ckg = "";
					$fuel="";
					$furltype = "";
					$year = "";
					$style = "";
					$maket = "";
					$AutoModeID="";
					$producer = "";
					$maketype = "";
					$market = "";

					for($n=0;$n<count($matchesprice[1]);$n++){
						$price=$price.preg_replace('|\s|','',$matchesprice[1][$n]).",";
						$enginesize=$enginesize.preg_replace('|\s|','',$matchesenginesize[1][$n])."L,";
						$trans=$trans.preg_replace('|\s|','',$matchestrans[1][$n])."档".preg_replace('|\s|','',$matchestrans1[1][$n]).",";
						$door=$door.preg_replace('|\s|','',$matchesdoor[1][$n])."门".preg_replace('|\s|','',$matchesseat[1][$n])."座".",";
						$ckg=$ckg.preg_replace('|\s|','',$matchesc[1][$n])."*".preg_replace('|\s|','',$matchesk[1][$n])."*".preg_replace('|\s|','',$matchesg[1][$n]).",";
						$fuel=$fuel.preg_replace('|\s|','',$matchesfuel[1][$n]).",";
					}


					$tmpa=$AutoModeID."\t".$siteid."\t".$id."\t".$producer."\t".$producer."\t".$brand."\t".$name."\t".$year."\t".$cartype."\t".$style."\t".$maketype."\t".$maket."\t".$salestatus."\t";
					$tmpb=$price."\t".$enginesize."\t".$fuel."\t".$trans."\t".$door."\t".$ckg;
					$result = $tmpa.$tmpb."\n";
					echo $result;

					//$filename="0507wy.txt";
					
				}
			}
		}
}

?>