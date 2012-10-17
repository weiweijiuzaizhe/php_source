<?php
header("Content-type: text/html; charset=utf-8");
ini_set('memory_limit', '-1');

$date=date("Ymd",strtotime("0 days"));$siteid=118;
mkdir("/home/lijue/spider/autocar/standard_auto/data/".$siteid."/");
mkdir("/home/lijue/spider/autocar/standard_auto/data/".$siteid."/".$date);
$filename="/home/lijue/spider/autocar/standard_auto/data/".$siteid."/".$date."/rawlocaldata.txt";


$url1 = "http://product.auto.163.com/cartype/";
$output_type = iconv('gbk','utf-8',url_fetch($url1));
$pattern_idfregment = '|<h5><span>(.{0,20})</span></h5> <span class=\"count\">\\(共<strong>(.{0,10})</strong>款\\)</span> <a class=\"return_top\" target=\"_self\" href=\"#top\">返回顶部</a></div>(.*)</div>|isU';
preg_match_all($pattern_idfregment, $output_type, $matchesidfreg);
$pattern_ins = '|<h5><a href=\"/series/(.{0,10}).html#0008F03\">(.{0,30})</a>(.{0,30})</h5>|isU'; // id name state
for($p=0;$p<count($matchesidfreg[1]);$p++){
   $cartype = $matchesidfreg[1][$p];
   $idfregment = $matchesidfreg[3][$p];
   preg_match_all($pattern_ins, $idfregment, $matchesins);
   for($m=0;$m<count($matchesins[1]);$m++){
     $id = $matchesins[1][$m];
     $name = $matchesins[2][$m];
     $status = preg_replace('|\s|','',$matchesins[3][$m]);
     if($status=="[未上市]")$salestatus ="未上市";
     if($status=="")$salestatus ="在售";
     if($status=="[停产]")$salestatus ="停产";
     $url2 = "http://product.auto.163.com/series/config1/".$id.".html";
     $output_canshu = iconv('gbk','utf-8',url_fetch($url2));
     
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
  
      for($n=0;$n<count($matchesprice[1]);$n++)
    {
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
file_put_contents($filename, $result, FILE_APPEND);
}}
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
}
*/
?>
