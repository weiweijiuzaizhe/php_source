<?php

header("Content-type: text/html; charset=utf-8");
ini_set('memory_limit', '-1');

$date=date("Ymd",strtotime("0 days"));

//网站对应的id
$siteid=27;

$path_of_file = "/home/wuwei/autocar/standard_auto/data/".$siteid."/";
//echo("$path_of_file\n");
mkdir($path_of_file);

$path_of_file = "/home/wuwei/autocar/standard_auto/data/".$siteid."/".$date;
//echo("$path_of_file\n");
mkdir($path_of_file);

$filename="/home/wuwei/spider/autocar/standard_auto/data/".$siteid."/".$date."/rawlocaldata.txt";



$url2= "http://newcar.xcar.com.cn/price_type.htm";//爱卡汽车车型大全的url
$output=url_fetch($url2);
$content = iconv("gbk","utf-8",$output);
$pattern_carid_mode = '|<a href=\"/(\w{0,20})/\" target=\"_blank\" >(.{0,20})</a>|isU';
preg_match_all($pattern_carid_mode, $content, $matchesbi);
$pattern_carid_mode = '|<a href=\"/(\w{0,20})/\" target=\"_blank\" class=\"gr_gh\" >(.{0,20})</a>|isU';
preg_match_all($pattern_carid_mode, $content, $matchesbi11);
$pattern_carid_mode = '|<a href=\"/(\w{0,20})/\" target=\"_blank\"  class=\"gr_gh\">(.{0,20})</a>|isU';
preg_match_all($pattern_carid_mode, $content, $matchesbi12);

//三种模式取下车型id
for ($m=0;$m<count($matchesbi[1]);$m++){

	$carid = $matchesbi[1][$m];
	$carmode=$matchesbi[2][$m];//carmode名字
	$status="上市";
	$url3="http://newcar.xcar.com.cn/".$carid."/config.htm";
	//echo("$url3\n");
	$output2=url_fetch($url3);
	$content2 = iconv("gbk","utf-8",$output2);
	$pattern_price = '|<td id=\"min_price_\d{1,9}\"><i>(.{0,20})</i></td>|isU';
	
	preg_match_all($pattern_price, $content2, $matchesbi2);
	$pattern_disl2 = '|<td id=\"m_disl2_\d{1,9}\">(.{0,20})</td>|isU';
	
	preg_match_all($pattern_disl2, $content2, $matchesbi7);
	
	$pattern_length= '|<td id="m_length_\d{1,9}">(.{0,20})</td>|isU';
	$pattern_width='|<td id="m_width_\d{1,9}">(.{0,20})</td>|isU';
	$pattern_height='|<td id="m_height_\d{1,9}">(.{0,20})</td>|isU';
	
	preg_match_all($pattern_length, $content2, $matcheslength);
	preg_match_all($pattern_width, $content2, $matcheswidth);
	preg_match_all($pattern_height, $content2, $matchesheight);

	$price = "";
	$volume = "";
	$size = "";

	for ($n=0;$n<count($matchesbi2[1]);$n++){
	
		$price = $matchesbi2[1][$n].",".$price;  //价格
		$volume = $matchesbi7[1][$n]."L".",".$volume;   //排量
		$size=$matcheslength[1][$n]."*".$matcheswidth[1][$n]."*".$matchesheight[1][$n].",".$size;//尺寸
		
	}
	
	$pattern_fty = '|<td id=\"bname_\d{1,9}\"><a href=\"/price/\w{0,15}/\" target=\"_blank\" title=\".{0,80}\">(.{0,80})</a></td>|isU';
	preg_match_all($pattern_fty, $content2, $matchesbi3);

	$factory=$matchesbi3[1][0];//厂商
	$pattern_type = '|<td id=\"type2_\d{1,9}\"><a href=\"/car/.{0,50}/\">(.{0,20})</a></td>|isU';
	preg_match_all($pattern_type, $content2, $matchesbi4);
	$pattern_transtype = '|<td id=\"m_transtype_\d{1,9}\">(.{0,50})</td>|isU';
	$type=$matchesbi4[1][0];                            //??
	preg_match_all($pattern_transtype, $content2, $matchesbi5);
	$trans=$matchesbi5[1][0];                        //变速器
	$pattern_fuel = '|<td id="m_fuel_\d{1,9}">(.{0,20})</td>|isU';
	preg_match_all($pattern_fuel, $content2, $matchesbi6);
	$fuel=$matchesbi6[1][0];                              //汽油
	$pattern_frame = '|<td id="m_frame2_\d{1,9}">(.{0,20})</td>|isU';
	preg_match_all($pattern_frame, $content2, $matchesbi8);
	$pattern_frame2 = '|<td id="m_door_\d{1,9}">(.{0,20})</td>|isU';
	preg_match_all($pattern_frame2, $content2, $matchesbi9);       //门
	$door = $matchesbi9[1][0]."门".$matchesbi8[1][0];

	$content4=preg_replace('/\s+/', '',$content2);
	$pattern_brand='|/price/pb\d{0,5}/\">(.*){1,30}</a>|isU';
	preg_match_all($pattern_brand, $content4, $matchesbrand);
	#print_r($matchesbrand);
	$brand= $matchesbrand[1][0];
	echo $brand."\n";

	$result = "27\t".$carid."\t".$factory."\t".$factory."\t".$brand."\t".$carmode."\t\t".$type."\t\t\t\t".$status."\t".$price."\t".$volume."\t".$fuel."\t".$trans."\t".$door."\t".$size."\n";
	echo $result;
	//$filename="aicar_style_brand_id.txt";
	file_put_contents($filename, $result, FILE_APPEND);

}



function url_fetch($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

	$to_return = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);
	return $to_return;
}


?>
