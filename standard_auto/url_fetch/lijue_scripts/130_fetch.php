<?php
$url1="http://data.auto.sina.com.cn/";
//$arr = array("n");
$arr=array("a00","a0","a","b","c","d","suv","mpv","s","n");
foreach ($arr as $type)
{
$url_type=$url1.$type."/";
$output_type=iconv('gbk','utf-8',url_fetch($url_type));
$pattern_idfregment = '|<ul class=\"clearfix\"><!-- (.{2})-->(.*)<div class=\"c2\">|isU';
preg_match_all($pattern_idfregment, $output_type, $matchesidfreg);
$salestatus = "";
$SiteID = "130";
for($p=0;$p<count($matchesidfreg[1]);$p++){
$status=$matchesidfreg[1][$p];
if($status=="wc")$salestatus ="未上市";
if($status=="zs")$salestatus ="在售";
if($status=="tc")$salestatus ="停产";
$idfregment=$matchesidfreg[2][$p];

$pattern_id = '|<a title=\"(.{0,30})\" href=\"http://data.auto.sina.com.cn/([0-9]{0,10})\" target=\"_blank\">|isU';
preg_match_all($pattern_id, $idfregment, $matchesid_name);
  for($m=0;$m<count($matchesid_name[1]);$m++)
  {
     $id=$matchesid_name[2][$m];$name=$matchesid_name[1][$m];
     $url2 = "http://data.auto.sina.com.cn/";
     $url_id = $url2.$id;
     $output_home=iconv('gbk','utf-8',url_fetch($url_id));
     $pattern_type = '|类型:<em><a href=\"http://data.auto.sina.com.cn/(.{0,10})\" target=\"_blank\">(.{0,30})</a>|isU';
     preg_match_all($pattern_type, $output_home, $matchestype);
     $cartype=$matchestype[2][0];

     $pattern_brand = '|<a href=\"http://data.auto.sina.com.cn/brands/(.{0,10})\" title=\"(.{0,30})\" target=\"_blank\">|isU';
     preg_match_all($pattern_brand, $output_home, $matchesbrand);
     $brand=$matchesbrand[2][0];
	 
     $pattern_producer = '|<a href=\"http://auto.sina.com.cn/salon/(.{0,80}).shtml\" title=\"(.{0,30})\" target=\"_blank\">|isU';
     preg_match_all($pattern_producer, $output_home, $matchesproducer);
     $producer = $matchesproducer[2][0];
	      
     $maketype="";

     $url_canshu = $url2."peizhi/".$id;
     $output_canshu=iconv('gbk','utf-8',url_fetch($url_canshu));
     $pattern_s = '|<td(.*)</td>|isU';

     $pattern_price = '|<p>指导价：<b class=\"red\">(.*)</p>|isU';
     preg_match_all($pattern_price, $output_canshu, $matchesprice);


     $pattern_enginesizefreg = '|<th>发动机排量\\(mL\\)</th>(.*)</tr>|isU';
     preg_match_all($pattern_enginesizefreg, $output_canshu, $matchesenginesizefreg);
     $enginesizefreg = $matchesenginesizefreg[1][0];
     preg_match_all($pattern_s, $enginesizefreg, $matchesenginesize);

     $pattern_transfreg = '|<th>档位数\\(个\\)</th>(.*)</tr>|isU';
     $pattern_trans1freg = '|<th>变速器类型</th>(.*)</tr>|isU';
     preg_match_all($pattern_transfreg, $output_canshu, $matchestransfreg);
     $transfreg = $matchestransfreg[1][0];
     preg_match_all($pattern_s, $transfreg, $matchestrans);
     preg_match_all($pattern_trans1freg, $output_canshu, $matchestrans1freg);
     $trans1freg = $matchestrans1freg[1][0];
     preg_match_all($pattern_s, $trans1freg, $matchestrans1);

     $pattern_doorfreg = '|<th>车门数</th>(.*)</tr>|isU';
     preg_match_all($pattern_doorfreg, $output_canshu, $matchesdoorfreg);
     $doorfreg = $matchesdoorfreg[1][0];
     preg_match_all($pattern_s, $doorfreg, $matchesdoor);
	 $pattern_seatfreg = '|<th>座位数</th>(.*)</tr>|isU';
     preg_match_all($pattern_doorfreg, $output_canshu, $matchesseatfreg);
     $seatfreg = $matchesseatfreg[1][0];
     preg_match_all($pattern_s, $seatfreg, $matchesseat);


     $pattern_cfreg = '|<th>长</th>(.*)</tr>|isU';
     preg_match_all($pattern_cfreg, $output_canshu, $matchescfreg);
     $cfreg = $matchescfreg[1][0];
     preg_match_all($pattern_s, $cfreg, $matchesc);
	 $pattern_kfreg = '|<th>宽</th>(.*)</tr>|isU';
     preg_match_all($pattern_kfreg, $output_canshu, $matcheskfreg);
     $kfreg = $matcheskfreg[1][0];
     preg_match_all($pattern_s, $kfreg, $matchesk);
	 $pattern_gfreg = '|<th>高</th>(.*)</tr>|isU';
     preg_match_all($pattern_gfreg, $output_canshu, $matchesgfreg);
     $gfreg = $matchesgfreg[1][0];
     preg_match_all($pattern_s, $gfreg, $matchesg);


     $pattern_fuelfreg = '|<th>燃油标号</th>(.*)</tr>|isU';
     preg_match_all($pattern_fuelfreg, $output_canshu, $matchesfuelfreg);
     $fuelfreg = $matchesfuelfreg[1][0];
     preg_match_all($pattern_s, $fuelfreg, $matchesfuel);
     $pattern_fueltypefreg = '|<th>燃料形式</th>(.*)</tr>|isU';
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
      for($n=0;$n<count($matchesprice[1]);$n++)
    {   // $price=$price.preg_replace('[\s|</b>]','',$matchesprice[1][$n]).",";
         $pric = preg_replace('[\s|</b>]','',$matchesprice[1][$n]);
         if($pric!="0.00万") $price=$price.$pric.",";
         $enginesiz=preg_replace('[\s|>|style=\"display:none;\"]','',$matchesenginesize[1][$n]);
		 if($enginesiz!="－") $enginesize=$enginesize.$enginesiz."ml,"; 
        
		$tran = preg_replace('[\s|>|style=\"display:none;\"]','',$matchestrans[1][$n]);
		$tran1 =preg_replace('[\s|变速器|>|style=\"display:none;\"]','',$matchestrans1[1][$n]);
        if($tran!="－"&&$tran1!="－")  $trans=$trans.$tran."档".$tran1.",";
        
		$doo = preg_replace('[\s|>|style=\"display:none;\"]','',$matchesdoor[1][$n]);
		$sea = preg_replace('[\s|>|style=\"display:none;\"]','',$matchesseat[1][$n]);
		if($doo!="－"&&$sea!="－") $door=$door.$doo."门".$sea."座".",";
         
		$c = preg_replace('[\s|>|style=\"display:none;\"]','',$matchesc[1][$n]);
		$k = preg_replace('[\s|>|style=\"display:none;\"]','',$matchesk[1][$n]);
		$g = preg_replace('[\s|>|style=\"display:none;\"|\\(+.+\\)|（+.+）]','',$matchesg[1][$n]);
		if($c!="－"&&$k!="－"&&$g!="－")$ckg=$ckg.$c."*".$k."*".$g.",";
		
		$oil = preg_replace('[\s|>|style=\"display:none;\"]','',$matchesfuel[1][$n]);
		$oiltype = preg_replace('[\s|>|style=\"display:none;\"]','',$matchesfueltype[1][$n]);
        if($oil!="－"&&$oiltype!="－") $fuel=$fuel.$oil."号".$oiltype.",";
         }

$tmpa=$AutoModeID."\t".$SiteID."\t".$id."\t".$producer."\t".$producer."\t".$brand."\t".$name."\t".$year."\t".$cartype."\t".$style."\t".$maketype."\t".$maket."\t".$salestatus."\t";
$tmpb=$price."\t".$enginesize."\t".$fuel."\t".$trans."\t".$door."\t".$ckg;
$result = $tmpa.$tmpb."\n";
echo $result;

$date=date("Ymd",strtotime("0 days"));$siteid=130;
mkdir("/home/lijue/spider/autocar/standard_auto/data/".$siteid."/");
mkdir("/home/lijue/spider/autocar/standard_auto/data/".$siteid."/".$date);
$filename="/home/lijue/spider/autocar/standard_auto/data/".$siteid."/".$date."/rawlocaldata.txt";

file_put_contents($filename, $result, FILE_APPEND);
}}}
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
