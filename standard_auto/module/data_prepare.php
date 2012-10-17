<?php
//导入函数
#require("/home/lijue/spider/autocar/standard_auto/function/url_fetch.php");
#require("/home/lijue/spider/autocar/standard_auto/function/pattern_match.php");
require("/home/lijue/spider/autocar/standard_auto/function/SQL_sel.php");
#require("/home/lijue/spider/autocar/standard_auto/function/myjoin.php");
#require("/home/lijue/spider/autocar/standard_auto/function/filetoarray.php");


//MVid_rawmodename删除老文件改为新文件
$test=SQL_sel("select ID,AutoModeCHN from Auto_Dim_AutoMode where Mark=0");
$filename="/home/lijue/spider/autocar/standard_auto/module/MVid_rawmodename.dat";
unlink($filename);
while ($key=each($test)){$content=$key["value"]."\n";
file_put_contents($filename, $content, FILE_APPEND);}


//MVid_localid删除老local增上新local表所有site的
$arr_site=file("./siteid_list.txt");
for($i=0;$i<count($arr_site);$i++)
{
$siteid=trim($arr_site[$i]);
$query="select AutoModeID,SiteAutoModeID from Auto_Fact_AutoMode_AutoSite where SiteID=".$siteid;
$filename="/home/lijue/spider/autocar/standard_auto/module/MVid_localid_".$siteid.".dat";
if(file_exists($filename))
{unlink($filename);}
$test=SQL_sel($query);
while ($key=each($test)){$content=$key["value"]."\n";
file_put_contents($filename, $content, FILE_APPEND);
}
}


?>
