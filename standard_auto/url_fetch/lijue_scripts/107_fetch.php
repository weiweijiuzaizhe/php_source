 <?php
 
header("Content-type: text/html; charset=utf-8");
ini_set('memory_limit', '-1');

$date=date("Ymd",strtotime("0 days"));$siteid=107;
mkdir("/home/lijue/spider/autocar/standard_auto/data/".$siteid."/");
mkdir("/home/lijue/spider/autocar/standard_auto/data/".$siteid."/".$date);
$filename="/home/lijue/spider/autocar/standard_auto/data/".$siteid."/".$date."/rawlocaldata.txt";


$url1 = "http://data.auto.qq.com/car_public/1/index_type.shtml";
$output_type = iconv('gbk','utf-8',url_fetch($url1));
$pattern_idfregment = '|<em><a name=\"(.{0,10})\">(.{0,30})</a></em>(.*)</ul>|isU';
preg_match_all($pattern_idfregment, $output_type, $matchesidfreg);
$pattern_ins = '|<a href=\"http://data.auto.qq.com/car_serial/(.{0,10})/index.shtml\" target=\"_blank\" >(.{0,30})</a>|isU'; // id name
for($p=0;$p<count($matchesidfreg[1]);$p++){
   $cartype = $matchesidfreg[2][$p];
   $idfregment = $matchesidfreg[3][$p];
   preg_match_all($pattern_ins, $idfregment, $matchesins);
   for($m=0;$m<count($matchesins[1]);$m++){
     $id = $matchesins[1][$m];
     $name = $matchesins[2][$m];     
     $url2 = "http://data.auto.qq.com/car_serial/".$id."/modelscompare.shtml";
     $output_canshu = iconv('gbk','utf-8',url_fetch($url2));
	 $pattern_brand = '|<a href=\"http://data.auto.qq.com/car_brand/(.{0,10})/index.shtml\" target=\"_blank\" title=\"(.{0,50})\">(.{0,50})</a>|isU';
     preg_match_all($pattern_brand, $output_canshu, $matchesbrand);
     $brand=$matchesbrand[3][0]; 
     $producer = $matchesbrand[3][0]; 
	 
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
     $SiteID = "";

	 for($q=0;$q<count($matchesfreg[1]);$q++){
	    $freg = $matchesfreg[1][$q];
	    preg_match_all($pattern_s, $freg, $matchess);
  	$maketype = preg_replace('/>/','',$matchess[1][5]);
		if($maketype=="国产") $maketype = "自主";
                 else $maketype = $maketype;	 
		   $price = $price.preg_replace('/>/','',$matchess[1][1])."万,";
		   $fuel = $fuel.preg_replace('/>/','',$matchess[1][2]).",";
		   $ckg = $ckg.preg_replace('/>/','',$matchess[1][6]).",";
		   $trans = $trans.preg_replace('/>/','',$matchess[1][10]).",";
		   $door = $door.preg_replace('/>/','',$matchess[1][17])."门".preg_replace('/>/','',$matchess[1][15])."座".",";
		   $enginesize = $enginesize.preg_replace('/>/','',$matchess[1][24])."ml,";
		   
}
     
      


$tmpa=$siteid."\t".$id."\t".$producer."\t".$producer."\t".$brand."\t".$name."\t".$year."\t".$cartype."\t".$style."\t".$maketype."\t".$maket."\t".$salestatus."\t";
$tmpb=$price."\t".$enginesize."\t".$fuel."\t".$trans."\t".$door."\t".$ckg;
$result = $tmpa.$tmpb."\n";
echo $result;
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
