<?php

header("Content-type: text/html; charset=utf-8");
ini_set('memory_limit', '-1');

$date=date("Ymd",strtotime("0 days"));$siteid=102;
mkdir("/home/lijue/spider/autocar/standard_auto/data/".$siteid."/");
mkdir("/home/lijue/spider/autocar/standard_auto/data/".$siteid."/".$date);
$filename="/home/lijue/spider/autocar/standard_auto/data/".$siteid."/".$date."/rawlocaldata.txt";

$url1="http://price.pcauto.com.cn/cars/";
//$arr=array("c76");
$arr=array("c76","c110","c73","c72","c71","c70","c74","c75","c111","c112");
foreach ($arr as $type)
{
$url_type=$url1.$type."/";
$output_type=iconv('gbk','utf-8',url_fetch($url_type));

$pattern_ins = '|<a title=\"(.{0,50})\" target=\"_blank\" href=\"http://price.pcauto.com.cn/serial.jsp\\?sid=(.{0,10})\">|isU';//
preg_match_all($pattern_ins, $output_type, $matchesins);
for($p=0;$p<count($matchesins[1]);$p++){  
     $id = $matchesins[2][$p];
     $name = $matchesins[1][$p];     
     $url2 = "http://price.pcauto.com.cn/serial.jsp?sid=".$id;
	 $output_home = iconv('gbk','utf-8',url_fetch($url2));
	 $pattern_brand = '|品牌：<a href=\"http://price.pcauto.com.cn/new_brand.jsp\\?bid=(.{0,10})\" target=\"_blank\" title=\"(.{0,50})\">|isU';
     $pattern_type = '|级别：<a href=\"http://price.pcauto.com.cn/cars/(.{0,10})/\" target=\"_blank\" title=\"(.{0,30})\">|isU';

	 preg_match_all($pattern_brand, $output_home, $matchesbrand);
     $brand=$matchesbrand[2][0]; 
	 preg_match_all($pattern_type, $output_home, $matchestype);
     $cartype=$matchestype[2][0]; 
     
	 $url3 = "http://price.pcauto.com.cn/serial_config.jsp?sid=".$id;
     $output_canshu = iconv('gbk','utf-8',url_fetch($url3));
	  
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
	 for($q=0;$q<count($matchesfreg[1]);$q++){
	    $freg = $matchesfreg[1][$q];
	    preg_match_all($pattern_s, $freg, $matchess);
		preg_match_all($pattern_price, $freg, $matchesprice);
		$price = $price.preg_replace('/--|\n/','',$matchesprice[2][0]).",";
		$fuel = $fuel.preg_replace('/--|\n/','',$matchess[1][24]).preg_replace('/--|\n/','',$matchess[1][10]).",";
        $ckg = preg_replace('/--|\s|\n/','',preg_replace('/x/','*',$ckg.$matchess[1][7])).",";
		$trans = $trans.preg_replace('/--|\n/','',$matchess[1][20]).",";
		$door = $door.preg_replace('/--|\n/','',$matchess[1][52])."座".",";
		
		$enginesize = $enginesize.preg_replace('/^\\./','0.',preg_replace('/--|\n|\s/','',$matchess[1][2]))."L,";
		
		   
}
     
      


$tmpa=$AutoModeID."\t".$SiteID."\t".$id."\t".$producer."\t".$producer."\t".$brand."\t".$name."\t".$year."\t".$cartype."\t".$style."\t".$maketype."\t".$maket."\t".$salestatus."\t";
$tmpb=$price."\t".$enginesize."\t".$fuel."\t".$trans."\t".$door."\t".$ckg;
$result = $tmpa.$tmpb."\n";
echo $result;

//$filename="0508tpy.txt";
file_put_contents($filename, $result, FILE_APPEND);
}
}          
/*
function url_fetch($uurl)
{$ch = curl_init();
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
}*/
?>
