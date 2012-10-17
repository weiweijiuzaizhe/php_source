<?php
header("Content-type: text/html; charset=utf-8");
ini_set('memory_limit', '-1');

$date=date("Ymd",strtotime("0 days"));$siteid=631;
mkdir("/home/lijue/spider/autocar/standard_auto/data/".$siteid."/");
mkdir("/home/lijue/spider/autocar/standard_auto/data/".$siteid."/".$date);
$filename="/home/lijue/spider/autocar/standard_auto/data/".$siteid."/".$date."/rawlocaldata.txt";

$url1 = "http://www.52che.com/auto/";
$output_type = iconv('gbk','utf-8',url_fetch($url1));
$pattern_idfregment = '|onclick=\"return false;\" >(.{0,30})</a>(.*)</div></div></div>|isU';
preg_match_all($pattern_idfregment, $output_type, $matchesidfreg);
$pattern_ins = '|<a href=\"http://price.52che.com/(.{0,30})/\" target=\"_blank\" title=\"(.{0,50})\">|isU'; // id name state
for($p=0;$p<count($matchesidfreg[1]);$p++){
   $cartype = $matchesidfreg[1][$p];
   $idfregment = $matchesidfreg[2][$p];
   preg_match_all($pattern_ins, $idfregment, $matchesins);
   for($m=0;$m<count($matchesins[1]);$m++){
     $id = $matchesins[1][$m];
     $name = $matchesins[2][$m];     
     $url2 = "http://price.52che.com/".$id."/param.html";
     $output_canshu = iconv('gbk','utf-8',url_fetch($url2));
     
	 $pattern_brand = '|<a href=\"http://www.52che.com/z/allcar.aspx\?type=bybrand#(.{0,30})\">|isU';
     preg_match_all($pattern_brand, $output_canshu, $matchesbrand);
     $brand=$matchesbrand[1][0];      
	 
	 $pattern_s = '|<td>(.*)</td>|isU';
	 
	 $pattern_producerfreg = '|厂商</td>(.*)</td>|isU';
     preg_match_all($pattern_producerfreg, $output_canshu, $matchesproducerfreg);
	 $producerfreg = $matchesproducerfreg[1][0];
	 preg_match_all($pattern_s, $producerfreg, $matchesproducer);
	 $producer = $matchesproducer[1][0];
	 
	 $pattern_pricefreg = '|厂商指导价\\(元\\)</td>(.*)</tr>|isU';
     preg_match_all($pattern_pricefreg, $output_canshu, $matchespricefreg);
	 $pricefreg = $matchespricefreg[1][0];
	 preg_match_all($pattern_s, $pricefreg, $matchesprice);
	 
     $pattern_enginesizefreg = '|排量\\(mL\\)</td>(.*)</tr>|isU';
     preg_match_all($pattern_enginesizefreg, $output_canshu, $matchesenginesizefreg);
     $enginesizefreg = $matchesenginesizefreg[1][0];
     preg_match_all($pattern_s, $enginesizefreg, $matchesenginesize);

     $pattern_transfreg = '|简称</td>(.*)</tr>|isU';
     preg_match_all($pattern_transfreg, $output_canshu, $matchestransfreg);
     $transfreg = $matchestransfreg[1][0];
     preg_match_all($pattern_s, $transfreg, $matchestrans);
	 
     $pattern_doorfreg = '|车门数\\(个\\)</td>(.*)</tr>|isU';
     preg_match_all($pattern_doorfreg, $output_canshu, $matchesdoorfreg);
     $doorfreg = $matchesdoorfreg[1][0];
     preg_match_all($pattern_s, $doorfreg, $matchesdoor);
	 $pattern_seatfreg = '|座位数\\(个\\)</td>(.*)</tr>|isU';
     preg_match_all($pattern_doorfreg, $output_canshu, $matchesseatfreg);
     $seatfreg = $matchesseatfreg[1][0];
     preg_match_all($pattern_s, $seatfreg, $matchesseat);

     $pattern_cfreg = '|长度\\(mm\\)</td>(.*)</tr>|isU';
     preg_match_all($pattern_cfreg, $output_canshu, $matchescfreg);
     $cfreg = $matchescfreg[1][0];
     preg_match_all($pattern_s, $cfreg, $matchesc);
	 $pattern_kfreg = '|宽度\\(mm\\)</td>(.*)</tr>|isU';
     preg_match_all($pattern_kfreg, $output_canshu, $matcheskfreg);
     $kfreg = $matcheskfreg[1][0];
     preg_match_all($pattern_s, $kfreg, $matchesk);
	 $pattern_gfreg = '|高度\\(mm\\)</td>(.*)</tr>|isU';
     preg_match_all($pattern_gfreg, $output_canshu, $matchesgfreg);
     $gfreg = $matchesgfreg[1][0];
     preg_match_all($pattern_s, $gfreg, $matchesg);

     $pattern_fuelfreg = '|燃油标号</td>(.*)</tr>|isU';
     preg_match_all($pattern_fuelfreg, $output_canshu, $matchesfuelfreg);
     $fuelfreg = $matchesfuelfreg[1][0];
     preg_match_all($pattern_s, $fuelfreg, $matchesfuel);
     $pattern_fueltypefreg = '|燃料形式</td>(.*)</tr>|isU';
     preg_match_all($pattern_fueltypefreg, $output_canshu, $matchesfueltypefreg);
     $fueltypefreg = $matchesfueltypefreg[1][0];
     preg_match_all($pattern_s, $fueltypefreg, $matchesfueltype);

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
	 $salestatus = ""; 
	 $maketype = "";
	 $market = "";

      for($n=0;$n<count($matchesprice[1]);$n++)
    {
         $price=$price.preg_replace('|\s|','',$matchesprice[1][$n]).",";
         $enginesize=$enginesize.preg_replace('|\s|','',$matchesenginesize[1][$n])."mL,";
         $trans=$trans.preg_replace('|\s|','',$matchestrans[1][$n]).",";
         $door=$door.preg_replace('|\s|','',$matchesdoor[1][$n])."门".preg_replace('|\s|','',$matchesseat[1][$n])."座".",";
         $ckg=$ckg.preg_replace('|\s|','',$matchesc[1][$n])."*".preg_replace('|\s|','',$matchesk[1][$n])."*".preg_replace('|\s|','',$matchesg[1][$n]).",";
         $fuel=$fuel.preg_replace('|\s|','',$matchesfuel[1][$n]).preg_replace('|\s|','',$matchesfueltype[1][$n]).",";
    }


$tmpa=$AutoModeID."\t".$siteid."\t".$id."\t".$producer."\t".$producer."\t".$brand."\t".$name."\t".$year."\t".$cartype."\t".$style."\t".$maketype."\t".$maket."\t".$salestatus."\t";
$tmpb=$price."\t".$enginesize."\t".$fuel."\t".$trans."\t".$door."\t".$ckg;
$result = $tmpa.$tmpb."\n";
echo $result;

//$filename="0507wac.txt";
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
}*/
?>
