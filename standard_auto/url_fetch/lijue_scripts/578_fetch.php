<?php
$date=date("Ymd",strtotime("0 days"));$siteid=578;
mkdir("/home/lijue/spider/autocar/standard_auto/data/".$siteid."/");
mkdir("/home/lijue/spider/autocar/standard_auto/data/".$siteid."/".$date);
$filename="/home/lijue/spider/autocar/standard_auto/data/".$siteid."/".$date."/rawlocaldata.txt";
$url1="http://www.autohome.com.cn/";
$arr=array("a00","a0","a","b","c","d","suv","mpv","s","p","mb");
foreach ($arr as $type)
{
$url_type=$url1.$type."/";
$output_type=iconv('gbk','utf-8',url_fetch($url_type));
$pattern_idfregment = '|id=\"div([1-3]{1})\">(.*)</div></div>|isU';
preg_match_all($pattern_idfregment, $output_type, $matchesidfreg);
$salestatus = "";

for($p=0;$p<count($matchesidfreg[1]);$p++){
$status=$matchesidfreg[1][$p];
if($status=="1")$salestatus ="未上市";
if($status=="2")$salestatus ="在售";
if($status=="3")$salestatus ="停产";
$idfregment=$matchesidfreg[2][$p];

$pattern_id = '|<a href=\'http://www.autohome.com.cn/(.{0,50})/\'>(.{0,30})</a>|isU';
preg_match_all($pattern_id, $idfregment, $matchesid_name);
  for($m=0;$m<count($matchesid_name[1]);$m++)
  {
     $id=$matchesid_name[1][$m];$name=preg_replace('[([)+([A-Z])+(])]','',$matchesid_name[2][$m]);
     $url2 = "http://www.autohome.com.cn/";
     $url_id = $url2.$id."/";
     $output_home=iconv('gbk','utf-8',url_fetch($url_id));
     $pattern_type = '|级别：<a href=\"/(.{0,10})/" target=\"_blank\">(.{0,30})</a>|isU';
     preg_match_all($pattern_type, $output_home, $matchestype);
     $cartype=$matchestype[2][0];     
     $pattern_brand = '|a href=\"http://car.autohome.com.cn/price/brand(.{0,50})\" target=\"_blank\">(.{0,30})</a>|isU';
     preg_match_all($pattern_brand, $output_home, $matchesbrand);
     $brand=$matchesbrand[2][0];
     $pattern_maketype = '|属性：(.{0,20})</span>|isU';
     preg_match_all($pattern_maketype, $output_home, $matchesmaketype);
     $maketype=$matchesmaketype[1][0];


     $url_canshu = $url_id."options.html";
     $output_canshu=iconv('gbk','utf-8',url_fetch($url_canshu));
     $pattern_s = '|<td class=\"smalltd\" id=\"config(.{0,10})\" >(.*)</td>|isU';

     $pattern_pricefreg = '|厂商指导价\\(元\\)</a>：</td>(.*)</tr>|isU';
     preg_match_all($pattern_pricefreg, $output_canshu, $matchespricefreg);
     $pricefreg = $matchespricefreg[1][0];
 


     preg_match_all($pattern_s, $pricefreg, $matchesprice);
 
     $pattern_enginesizefreg = '|排量\\(ml\\)</a>：</td>(.*)</tr>|isU';
     preg_match_all($pattern_enginesizefreg, $output_canshu, $matchesenginesizefreg);
     $enginesizefreg = $matchesenginesizefreg[1][0];
     preg_match_all($pattern_s, $enginesizefreg, $matchesenginesize);

     $pattern_transfreg = '|变速箱</a>：</td>(.*)</tr>|isU';
     preg_match_all($pattern_transfreg, $output_canshu, $matchestransfreg);
     $transfreg = $matchestransfreg[1][0];
     preg_match_all($pattern_s, $transfreg, $matchestrans);
     
     $pattern_doorfreg = '|车身结构</a>：</td>(.*)</tr>|isU';
     preg_match_all($pattern_doorfreg, $output_canshu, $matchesdoorfreg);
     $doorfreg = $matchesdoorfreg[1][0];
     preg_match_all($pattern_s, $doorfreg, $matchesdoor);
     

     $pattern_ckgfreg = '|长×宽*×高\\(mm\\)</a>：</td>(.*)</tr>|isU';
     preg_match_all($pattern_ckgfreg, $output_canshu, $matchesckgfreg);
     $ckgfreg = $matchesckgfreg[1][0];
     preg_match_all($pattern_s, $ckgfreg, $matchesckg);
     

     $pattern_fuelfreg = '|燃油标号</a>：</td>(.*)</tr>|isU';
     preg_match_all($pattern_fuelfreg, $output_canshu, $matchesfuelfreg);
     $fuelfreg = $matchesfuelfreg[1][0];
     preg_match_all($pattern_s, $fuelfreg, $matchesfuel);
     

     $pattern_fueltypefreg = '|燃料形式</a>：</td>(.*)</tr>|isU';
     preg_match_all($pattern_fueltypefreg, $output_canshu, $matchesfueltypefreg);
     $fueltypefreg = $matchesfueltypefreg[1][0];
     preg_match_all($pattern_s, $fueltypefreg, $matchesfueltype);
     

     $pattern_producerfreg = '|厂商</a>：</td><td class=\"smalltd\" id=\"config(.{0,10})\" >(.*)</td>|isU';
     preg_match_all($pattern_producerfreg, $output_canshu, $matchesproducerfreg);
     $producerfreg = $matchesproducerfreg[2][0];
     
     
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



      for($n=0;$n<count($matchesprice[2]);$n++)
    {
         $price=$price.$matchesprice[2][$n].",";
         $enginesize=$enginesize.$matchesenginesize[2][$n]."ml,";
         $trans=$trans.$matchestrans[2][$n].",";
         $door=$door.$matchesdoor[2][$n].",";
         $ckg=$ckg.$matchesckg[2][$n].",";
         $fuel=$fuel.$matchesfuel[2][$n].$matchesfueltype[2][$n].",";
    }


$tmpa=$AutoModeID."\t".$siteid."\t".$id."\t".$producerfreg."\t".$producerfreg."\t".$brand."\t".$name."\t".$year."\t".$cartype."\t".$style."\t".$maketype."\t".$maket."\t".$salestatus."\t";
$tmpb=$price."\t".$enginesize."\t".$fuel."\t".$trans."\t".$door."\t".$ckg;
$result = $tmpa.$tmpb."\n";
echo $result;

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
