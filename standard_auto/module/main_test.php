<?php
//导入函数
$path="/home/bi/standard_auto/";
$fpath=$path."function"."/";
$mpath=$path."module"."/";
$dpath=$path."data"."/";
require($fpath."conv_strcoltofrq.php");
require($fpath."myjoin.php");
require($fpath."column_del.php");
require($fpath."filetoarray.php");
require($fpath."arraytofile.php");
require($fpath."url_fetch.php");
require($fpath."pattern_match.php");
require($mpath."rule1_convert.php");
require($mpath."rule2_mysizejoin.php");


//初始化sql上的查询表
require($mpath."data_prepare.php");


//获取日期和siteid并建立工作目录，目前siteid_list.txt只有578，可以增加其他汽车网站的id抓取其他网站信息
$date=date("Ymd",strtotime("0 days"));
$arr_site=filetoarray("./siteid_list.txt");
for($i=0;$i<count($arr_site);$i++)
{$siteid=$arr_site[$i];//这个循环套到最后遍历siteid


$workpath=$dpath.$siteid."/".$date."/";

//页面抓取
require_once($path.$siteid."_fetch.php");


//第一步转化车身尺寸输出样式,车名转为小写
$filename1=$workpath."rawlocaldata.txt";

$arr_rawlocaldata=filetoarray($filename1);
$arr_rawdata_conv_1=conv_strcoltofrq($arr_rawlocaldata,18);
$arr_rawdata_conv_2=conv_strcoltofrq($arr_rawdata_conv_1,17);
$arr_rawdata_conv_3=conv_strcoltofrq($arr_rawdata_conv_2,16);
$arr_rawdata_conv_4=conv_strcoltofrq($arr_rawdata_conv_3,15);
$arr_rawdata_conv=rule1_convert($arr_rawdata_conv_4,6);
unlink($filename1);
arraytofile($arr_rawdata_conv,$filename1);



//local表匹配
$localfile=$mpath."MVid_localid_".$siteid.".dat";
if(file_exists($localfile))
{
$locarr_1=filetoarray($localfile);
$locarr_2=filetoarray($filename1);
$locarr_3=myjoin($locarr_1,2,$locarr_2,2,"join");
arraytofile($locarr_3,$workpath."local_".$siteid);
$locarr_r=myjoin($locarr_1,2,$locarr_2,2,"right");
arraytofile($locarr_r,$workpath."rlocal_".$siteid);
}

//MODEname匹配
$modenamefile=$mpath."MVid_rawmodename.dat";
$modarr=filetoarray($modenamefile);
$modarr_1=rule1_convert($modarr,2);
$mmodarr=filetoarray($filename1);
$modarr_2=rule1_convert($mmodarr,6);
$modarr_3=myjoin($modarr_1,2,$modarr_2,6,"join");
arraytofile($modarr_3,$workpath."mode_".$siteid);
$modarr_r=myjoin($modarr_1,2,$modarr_2,6,"right");
arraytofile($modarr_r,$workpath."rmode_".$siteid);

//使用车身尺寸匹配test
$sizefile=$mpath."MVid_autosize_v3.txt";
$arr_1=filetoarray($sizefile);
$arr_2=filetoarray($filename1);
$arr_3=rule2_mysizejoin($arr_1,2,$arr_2,18,"join");
arraytofile($arr_3,$workpath."size_".$siteid);
$arr_r=rule2_mysizejoin($arr_1,2,$arr_2,18,"right");
arraytofile($arr_r,$workpath."rsize_".$siteid);

//合并rlocal+rmode+rsize，统计每行次数，三个都出现的，并且价格不为空，生成候选文件

$a=filetoarray($workpath."rlocal_".$siteid);
$b=filetoarray($workpath."rmode_".$siteid);
$c=filetoarray($workpath."rsize_".$siteid);
$d=array_merge($a,$b,$c);
#echo count($a)." ".count($b)." ".count($c)." ".count($d)."\n";
$e=array_count_values($d);
while($key=each($e)){
	
	$name=explode("\t",$key["key"]);
if($key["value"]==3)
	{
if(preg_match("/\d/",$name[12]))
  {$content=$key["key"]."\n";
   $filename=$workpath."rfornewid_".$siteid;
   file_put_contents($filename,$content,FILE_APPEND);
  }    }	
  }

//local表新增的已有modeid对应的文件((mode+size) right join local)
$a=filetoarray($workpath."local_".$siteid);
$a=column_del($a,2);
$b=filetoarray($workpath."mode_".$siteid);
$b=column_del($b,2);
$c=filetoarray($workpath."size_".$siteid);
$c=column_del($c,2);
$d=array_merge($b,$c);
$e=array_unique($d);
#echo count($a)." ".count($b)." ".count($c)." ".count($d)." ".count($e)."\n";
$f=myjoin($a,3,$e,3,"right");
echo count($f)."/n";
while($key=each($f)){
$content=$key["value"]."\n";
   $filename=$workpath."ExmvidVsNewlocalid_".$siteid;
   file_put_contents($filename,$content,FILE_APPEND);
}
}
?>
