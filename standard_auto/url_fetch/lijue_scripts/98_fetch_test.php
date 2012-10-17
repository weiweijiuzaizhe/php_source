#!/usr/bin/php
<?php

header("Content-type: text/html; charset=utf-8");
ini_set('memory_limit', '-1');

$date=date("Ymd",strtotime("0 days"));$siteid=98;
mkdir("/home/lijue/spider/autocar/standard_auto/data/".$siteid."/");
mkdir("/home/lijue/spider/autocar/standard_auto/data/".$siteid."/".$date);
$filename="/home/lijue/spider/autocar/standard_auto/data/".$siteid."/".$date."/rawlocaldata.txt";

$count=0;
for($i=110000;$i<=130000;$i++)
{ 
   $url = 'http://db.auto.sohu.com/PARA/TRIMDATA/trim_data_'.$i.'.json';   
//$contents = file_get_contents($url);$decodecon = unicode_urldecode($contents);echo $decodecon;}   
$automode=automode_fetch($url);

if ($automode != null)
{$automode_arr[$count]=$automode;$count+=1;}
}

$bb=mysort($automode_arr);
$cc=vertoline($bb);

while($key=each($cc)){$content=$key["value"]."\n";file_put_contents($filename,$content,FILE_APPEND);}


//以下是函数功能区
function automode_fetch($url)
{
	$contents = file_get_contents($url);
	$decodecon = unicode_urldecode($contents);
	$type = explode(',',$decodecon);
	echo substr($type[0],0,11);
  if( substr($type[0],0,11) == "{'SIP_T_ID'" )
  {  
	$autoitem0 = explode(':',$type[0]);$autoitem0 = explode("'",$autoitem0[1]); //汽车型号		
    	$autoitem1 = explode(':',$type[2]);$autoitem1 = explode("'",$autoitem1[1]); //modeid
		$autoitem2 = explode(':',$type[3]);$autoitem2 = explode("'",$autoitem2[1]); //modename
		$autoitem3 = explode(':',$type[5]);$autoitem3 = explode("'",$autoitem3[1]); //oil
		$autoitem4 = explode(':',$type[6]);$autoitem4 = explode("'",$autoitem4[1]); //price
		$autoitem5 = explode(':',$type[12]);$autoitem5 = explode("'",$autoitem5[1]); //厂商
		$autoitem6 = explode(':',$type[13]);$autoitem6 = explode("'",$autoitem6[1]); //汽车级别
		$autoitem7 = explode(':',$type[14]);$autoitem7 = explode("'",$autoitem7[1]); //门数
		$autoitem8 = explode(':',$type[18]);$autoitem8 = explode("'",$autoitem8[1]); //手动自动
		$autoitem9 = explode(':',$type[25]);$autoitem9 = explode("'",$autoitem9[1]); //长
		$autoitem10 = explode(':',$type[26]);$autoitem10 = explode("'",$autoitem10[1]); //宽
		$autoitem11 = explode(':',$type[27]);$autoitem11 = explode("'",$autoitem11[1]); //高
		$autoitem12 = explode(':',$type[55]);$autoitem12 = explode("'",$autoitem12[1]); //燃料
                $autoitem13 = explode(' ',$autoitem2[1]);//品牌+modename
           

		$automode = "98\t".$autoitem1[1]."\t".$autoitem5[1]."\t".$autoitem5[1]."\t".$autoitem13[0]."\t".$autoitem13[1]."\t0\t".$autoitem6[1]."\t\t\t\t\t".$autoitem4[1]."\t".$autoitem3[1]."\t".$autoitem12[1]."\t".$autoitem8[1]."\t".$autoitem7[1]."\t".$autoitem9[1]."*".$autoitem10[1]."*".$autoitem11[1];
				
	}				
return $automode;
}

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



function mysort($a)
{
for($i=0;$i<count($a);$i++)
{
$bi=explode("\t",$a[$i]);
$b_sort[$i]=$bi[1];//挖取排序列，0为第一列
}
asort($b_sort);
$j=0;
while ($kev=each($b_sort))
{$key= $kev['key'];
$result[$j]=$a[$key];//排序列排序后的索引关联原数组
$j+=1;}
return $result;
}
function vertoline($arr)
{$key=array();$content=array();$merge=array();$j=-1;
for($i=0;$i<count($arr);$i++)
{
$arrstr=explode("\t",$arr[$i]);//二维数组行数+列数

if($arrstr[1]==$key[$j])//选择，作为id的列值
{
for($m=12;$m<18;$m++){
$content[$m][$j]=$content[$m][$j].",".$arrstr[$m];}

}//选择，要合并的列赋值
else
{$j+=1;$key[$j]=$arrstr[1];//作为id的列植
for($m=1;$m<count($arrstr);$m++)
{
$content[$m][$j]=$arrstr[$m];
$content[$m][$j]=$arrstr[$m];
}
}//不合并的列赋值
}
for($j=0;$j<count($key);$j++)
{$merge[$j]=$key[$j];
for($m=1;$m<count($arrstr);$m++)
{$merge[$j]=$merge[$j]."\t".$content[$m][$j];
}
}
return $merge;
} 

/*
function arraytofile($arr,$filename)
{while($key=each($arr)){$content=$key["value"]."\n";
file_put_contents($filename,$content,FILE_APPEND);}
}

function filetoarray($filename){
$file = fopen($filename,"r") or exit("Unable to open file!");
$i=0;$file_arr=array();
while(!feof($file))
{ $file_arr[$i]=fgets($file);
$file_arr[$i]=trim($file_arr[$i]);
$i+=1;}
fclose($file);
array_pop($file_arr);
return $file_arr;
}
*/
?>
