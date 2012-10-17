<?php

header("Content-type: text/html; charset=utf-8");
ini_set('memory_limit', '-1');

$date=date("Ymd",strtotime("0 days"));$siteid=622;
mkdir("/home/lijue/spider/autocar/standard_auto/data/".$siteid."/");
mkdir("/home/lijue/spider/autocar/standard_auto/data/".$siteid."/".$date);
$filename="/home/lijue/spider/autocar/standard_auto/data/".$siteid."/".$date."/rawlocaldata.txt";

$arr=array("微型车"=>"weixingche","小型车"=>"xiaoxingche","紧凑型车"=>"jincouxingche","中型车"=>"zhongxingche","中大型车"=>"zhongdaxingche","豪华车"=>"haohuaxingche","MPV"=>"mpv","SUV"=>"suv","跑车"=>"paoche","面包车"=>"mianbaoche");
$count=0;
foreach ($arr as $key=>$value) 
{
	echo $key,":",$value,"\n";
$type=$value;
$typename=$key;

$url2= "http://car.bitauto.com/".$type;

echo $url2."\n";

$output=url_fetch($url2);

$pattern_body_carid = '|<div class=\"line_box jb0525_01\">(.*)<div class=\"hideline\">|isU';
preg_match_all($pattern_body_carid, $output, $matchesbi);
$result_bi = $matchesbi[1][0];
$pattern_body_carid_2 = '|<a href=\"http://car.bitauto.com/(\w{0,20})/\" target=\"_blank\">(.{0,20})</a>|isU';
preg_match_all($pattern_body_carid_2, $result_bi, $matchesbi2);
;

for ($m=0;$m<count($matchesbi2[1]);$m++)
{
	
	$carid=$matchesbi2[1][$m];
	echo $carid;
	$modename=$matchesbi2[2][$m];
	$url3="http://car.bitauto.com/".$carid;
	$output2=url_fetch($url3);
	$pattern_changshang = '|<label>厂商：</label><a class=\"cBlack\" href=\"/\w{0,20}/\" target=\"_blank\">(.{0,20})</a>|isU';
	preg_match_all($pattern_changshang, $output2, $matchescs);
	$changshang=$matchescs[1][0];
	$pattern_brand = '|target=\"_blank\"><img alt=\"(.{0,40})\"|isU';
        preg_match_all($pattern_brand, $output2, $matchesbd);
        $brand=$matchesbd[1][0];
	
	//配置页面
	$url3="http://car.bitauto.com/".$carid."/peizhi";
	
	$output3=url_fetch($url3);
	$pattern_peizhiurl = '|<script type=\"text/javascript\" src=\"(.{0,250})\"></script>|isU';
	preg_match_all($pattern_peizhiurl, $output3, $matchespzu);
	$peizhiurl=$matchespzu[1][1];
        $peizhiall=url_fetch($peizhiurl);
        $peizhi_2 = preg_replace("#\"#","",$peizhiall);
$peizhi_3 = preg_split("#]],\[\[#",$peizhi_2);

$price="";
$volume="";
$door="";$alter="";$size="";

foreach ($peizhi_3 as $key)
{
$peizhi_4 = preg_split("#],\[#",$key);

$pricegroup = $peizhi_4[1];
$price_item = preg_split("#,#",$pricegroup);
$price = $price_item[0].",".$price;
$volume = $price_item[3]."L".",".$volume;
$alter = $price_item[4]."档".$price_item[5].",".$alter;

$doorgroup = $peizhi_4[3];
$door_item = preg_split("#,#",$doorgroup);
$door = $door_item[0]."门".$door_item[1].",".$door;

$oilgroup = $peizhi_4[6];
$oil_item = preg_split("#,#",$oilgroup);
$oil = $oil_item[2].$oil_item[3];

$sizegroup=$peizhi_4[4];
$size_item = preg_split("#,#",$sizegroup);
$size = $size_item[0]."*".$size_item[1]."*".$size_item[2].",".$size;

}
	
 $result_bi2[$count] = "622\t".$carid."\t".$changshang."\t".$changshang."\t".$brand."\t".$modename."\t\t".$typename."\t\t\t\t\t".$price."\t".$volume."\t".$oil."\t".$alter."\t".$door."\t".$size;
	
echo $result_bi2[$count]."\n";

file_put_contents($filename,$result_bi2[$count]."\n" , FILE_APPEND);
	
$count+=1;
}


}


?>
