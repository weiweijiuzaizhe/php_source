<?php

header("Content-type: text/html; charset=utf-8");
ini_set('memory_limit', '-1');

$date=date("Ymd",strtotime("0 days"));$siteid=98;
mkdir("/home/lijue/spider/autocar/standard_auto/data/".$siteid."/");
mkdir("/home/lijue/spider/autocar/standard_auto/data/".$siteid."/".$date);
$filename="/home/lijue/spider/autocar/standard_auto/data/".$siteid."/".$date."/rawlocaldata.txt";


$url1="http://db.auto.sohu.com/";
$arr=array("a00","a0","a","b","c","luxury","mpv","suv","sportscars");
foreach ($arr as $type)
{
$url_type=$url1.$type.".shtml";
$output_type=iconv('gbk','utf-8',url_fetch($url_type));
$pattern_idfregment = '|<div id=\"modelList\">(.*)<div class=\"cf\"></div>|isU';
preg_match_all($pattern_idfregment, $output_type, $matchesidfreg);
$freg = $matchesidfreg[1][0];
$pattern_ins = '|<a href=\"/model-(.{0,10}).shtml\" target=\"_blank\">(.{0,50})</a>|isU';//
preg_match_all($pattern_ins, $freg, $matchesins);
for($p=0;$p<count($matchesins[1]);$p++){
     $id = $matchesins[1][$p];
     $name = $matchesins[2][$p];
     $url2 = "http://db.auto.sohu.com/PARA/MODEL/model_para_".$id.".json";
     $output_home = iconv('gbk','utf-8',url_fetch($url2));
     $pattern_ck = '|SIP_T_ID\':\'(.{0,10})\',|isU';
     preg_match_all($pattern_ck, $output_home, $matchesck);
     $price = "";
     $fuel = "";
     $ckg = "";
     $enginesize = "";
     $cartype = "";
     $brand = "";
     $producer = "";$door="";$trans="";
     for($m=0;$m<count($matchesck[1]);$m++){
         $ckid=$matchesck[1][$m];
         $url = 'http://db.auto.sohu.com/PARA/TRIMDATA/trim_data_'.$ckid.'.json';
         $contents = file_get_contents($url);$decodecon = unicode_urldecode($contents);
                //echo $decodecon;
         $contents = file_get_contents($url);
		 $decodecon = unicode_urldecode($contents);
         $type = explode(',',$decodecon);
            //echo substr($type[0],0,11);
         if( substr($type[0],0,11) == "{'SIP_T_ID'" )
         {
                $autoitem2 = explode(':',$type[3]);$autoitem2 = explode("'",$autoitem2[1]);//modename
                $autoitem3 = explode(':',$type[5]);$autoitem3 = explode("'",$autoitem3[1]); //排量
                $autoitem4 = explode(':',$type[6]);$autoitem4 = explode("'",$autoitem4[1]); //price
                $price = $price.$autoitem4[1]."万," ;
                $autoitem5 = explode(':',$type[12]);$autoitem5 = explode("'",$autoitem5[1]); //厂商
                $producer = $autoitem5[1];
                $autoitem6 = explode(':',$type[13]);$autoitem6 = explode("'",$autoitem6[1]);//汽车级别
                $cartype = $autoitem6[1];
                $autoitem7 = explode(':',$type[14]);$autoitem7 = explode("'",$autoitem7[1]); //门数
                $door = $door.$autoitem7[1];
                $autoitem8 = explode(':',$type[18]);$autoitem8 = explode("'",$autoitem8[1]); //手动自动
                $trans = $trans.$autoitem8[1].",";
                $autoitem9 = explode(':',$type[25]);$autoitem9 = explode("'",$autoitem9[1]); //长
                $autoitem10 = explode(':',$type[26]);$autoitem10 = explode("'",$autoitem10[1]); //宽
                $autoitem11 = explode(':',$type[27]);$autoitem11 = explode("'",$autoitem11[1]); //高
                $autoitem12 = explode(':',$type[55]);$autoitem12 = explode("'",$autoitem12[1]); //燃料
                $autoitem13 = explode(' ',$autoitem2[1]);//品牌+modename
                $enginesize = $enginesize.$autoitem3[1]."L,";
                $fuel = $fuel.$autoitem12[1].",";
                $ckg = $ckg.$autoitem9[1]."*".$autoitem10[1]."*".$autoitem11[1].",";
                $brand = $autoitem13[1];

        }
 
    }
       $automode = $producer."\t".$producer."\t".$brand."\t".$name."\t0\t".$cartype."\t\t\t\t\t".$price."\t".$enginesize."\t".$fuel."\t".$trans."\t".$door."\t".$ckg;
    $AutoModeID="";
    $SiteID = "98";
    $result = $AutoModeID."\t".$siteid."\t".$id."\t".$automode."\n";
    echo $result;
//$filename="0508souhu.txt";
//file_put_contents($filename, $result, FILE_APPEND);
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


function unicode_urldecode($url)
{
   preg_match_all('/%u([[:alnum:]]{4})/', $url, $a);

   foreach ($a[1] as $uniord)
   {
       $dec = hexdec($uniord);
       $utf = '';

       if ($dec < 128)
       {
           $utf = chr($dec);
       }
       else if ($dec < 2048)
       {
           $utf = chr(192 + (($dec - ($dec % 64)) / 64));
           $utf .= chr(128 + ($dec % 64));
       }
       else
       {
           $utf = chr(224 + (($dec - ($dec % 4096)) / 4096));
           $utf .= chr(128 + ((($dec % 4096) - ($dec % 64)) / 64));
           $utf .= chr(128 + ($dec % 64));
       }

       $url = str_replace('%u'.$uniord, $utf, $url);
   }

   return urldecode($url);
}


?>
