<?php
//凤凰汽车http://car.auto.ifeng.com


header("Content-type: text/html; charset=utf-8");
ini_set('memory_limit', '-1');

$date=date("Ymd",strtotime("0 days"));$siteid=49;
mkdir("/home/lijue/spider/autocar/standard_auto/data/".$siteid."/");
mkdir("/home/lijue/spider/autocar/standard_auto/data/".$siteid."/".$date);
$filename="/home/lijue/spider/autocar/standard_auto/data/".$siteid."/".$date."/rawlocaldata.txt";
$arr=array("其他","微型车","小型车","紧凑型车","中型车","中大型车","豪华车","MPV","SUV","跑车");
	for ($i=0; $i<9; $i++){	
	
// http://car.auto.ifeng.com/type1 http://car.auto.ifeng.com/type2
		$url3="http://car.auto.ifeng.com/type".$i;
		$type=$arr[$i];//级别
		
		//得到分类页面：
		$output2=url_fetch($url3);
		
		$pattern_mode = '|<span class=\"name\"><a href=\"/series/(\d{3,5})\" title=\"(.*){0,50}\" target=\"_blank\">|isU';
		preg_match_all($pattern_mode, $output2, $matchesmode);
		for ($j=0; $j<count($matchesmode[1]); $j++){
			$modeid=$matchesmode[1][$j];//id
		$modename=$matchesmode[2][$j];//mode名字
		$url4="http://car.auto.ifeng.com/series/".$modeid."/spec";
		
		//得到指定车型的内容：
		$output4=url_fetch($url4);
		
		$pattern_brand='|<a href=\"http://car.auto.ifeng.com/brand/\d{3,5}\" target=\"_blank\">(.*){0,50}</a>|isU';
		$pattern_factory='|<a href=\"http://car.auto.ifeng.com/producer/\d{3,5}\" target=\"_blank\">(.*){0,50}</a>|isU';
		preg_match_all($pattern_brand, $output4, $matchesb);
		preg_match_all($pattern_factory, $output4, $matchesf);
		
		$brand=$matchesb[1][0];
		$factory=$matchesf[1][0];
		$p_price1='|厂商指导价(.*)</tr>|isU';
		preg_match_all($p_price1, $output4, $matchesp1);
		$price2=preg_replace('/\s+/', '',$matchesp1[1][0]);
		$p_price2='|<tdclass=\"smalltd\">(\d{1,5}\.\d{1,3})万元|isU';
		preg_match_all($p_price2, $price2, $matchesp2);

		$price3="";
		for($m=0; $m<count($matchesp2[1]); $m++){
			$price3=$matchesp2[1][$m].",".$price3;//价格
		}
		
		$p_volume1='|发动机(.*)<tr id=\"hidd\">|isU';
		preg_match_all($p_volume1, $output4, $matchesp1);
		$volume2=preg_replace('/\s+/', '',$matchesp1[1][0]);
		$p_volume2='|<tdclass=\"smalltd\">(.*){2,5}L|isU';
		preg_match_all($p_volume2, $volume2, $matchesp2);
		
		$volume3="";
		for($m=0; $m<count($matchesp2[1]); $m++){
			if ($matchesp2[1][$m]!="-</td><tdc"){
				$volume3=$matchesp2[1][$m].",".$volume3;}//排量
		}

		$p_oil1='|燃料形式(.*)燃油标号|isU';
		preg_match_all($p_oil1, $output4, $matchesp1);
		$oil2=preg_replace('/\s+/', '',$matchesp1[1][0]);

		$p_oil2='|<tdclass=\"smalltd\">(.*){2,10}</td>|isU';
		preg_match_all($p_oil2, $oil2, $matchesp2);

		$oil3="";
		for($m=0; $m<count($matchesp2[1]); $m++){
			if ($matchesp2[1][$m]!="-</td><tdc"){
				$oil3=$matchesp2[1][$m].",".$oil3;}//汽油
		}


		$p_tran1='|变速箱类型(.*)底盘参数|isU';
		preg_match_all($p_tran1, $output4, $matchesp1);
		$tran2=preg_replace('/\s+/', '',$matchesp1[1][0]);
		$p_tran2='|<tdclass=\"smalltd\">(.*){0,10}</td>|isU';
		preg_match_all($p_tran2, $tran2, $matchesp2);

		$tran3="";
		for($m=0; $m<count($matchesp2[1]); $m++){
			if ($matchesp2[1][$m]!="-</td><tdc"){
				$tran3=$matchesp2[1][$m].",".$tran3;
			}//档位
	}	
	
	
	$p_door1='|车门数(.*)座位数|isU';
	preg_match_all($p_door1, $output4, $matchesp1);
	$door2=preg_replace('/\s+/', '',$matchesp1[1][0]);
	$p_door2='|<tdclass=\"smalltd\">(.*){1,5}</td>|isU';
	preg_match_all($p_door2, $door2, $matchesp2);

	$door3="";
	for($m=0; $m<count($matchesp2[1]); $m++){
		if ($matchesp2[1][$m]!="-"){
			$door3=$matchesp2[1][$m].",".$door3;
		}//门箱
	}	
	
	$p_size1='|长×宽×高\(mm\)(.*)车身结构|isU';
	preg_match_all($p_size1, $output4, $matchesp1);
	$size2=preg_replace('/\s+/', '',$matchesp1[1][0]);
	$p_size2='|<tdclass=\"smalltd\">(.*){2,20}</td>|isU';
	preg_match_all($p_size2, $size2, $matchesp2);

	$size3="";
	for($m=0; $m<count($matchesp2[1]); $m++){
	if ($matchesp2[1][$m]!="-</td><tdc"){
		$tmp=$matchesp2[1][$m];
		$tmp=substr($tmp, 0, 4)."*".substr($tmp, 6, 4)."*".substr($tmp, 12, 4);
		$size3=$tmp.",".$size3;}//尺寸
	}	
	
	$mode = "49\t".$modeid."\t".$factory."\t".$factory."\t".$brand."\t".$modename."\t\t".$type."\t\t\t\t\t".$price3."\t".$volume3."\t".$oil3."\t".$tran3."\t".$door3."\t".$size3."\n";
	$mode=preg_replace('/-\*\*,/',"",$mode);$mode=preg_replace('/-,/',"",$mode);$mode=preg_replace('/\?,/',"",$mode);$mode=preg_replace('/\?\*\*,/',"",$mode);


	file_put_contents($filename,$mode, FILE_APPEND);
	}
}

function url_fetch($uurl){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $uurl);
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


?>
