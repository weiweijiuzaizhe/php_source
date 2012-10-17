<?php
#$database="mediavolap";
#$query="select ID,AutoModeCHN from Auto_Dim_AutoMode where ID <20";
#$test=SQL_sel($query);
#while ($key=each($test))
#{echo $key["value"]."\n";
#}
function SQL_sel($query)
{
$database="mediavolap";
$dbuser="lijue";
$dbpass="lijuemediav";
$dbhost="research.rw.prod.mediav.com";
$dbport="30017";
$characterset="gbk";
$id=mysql_connect($dbhost.":".$dbport,$dbuser,$dbpass);
mysql_query("SET CHARACTER SET ".$characterset);
mysql_select_db($database,$id);
$query = iconv("utf-8","gbk",$query);
$result=mysql_query($query,$id);
$datanum=mysql_num_rows($result);
$content=array();
for($i=1;$i<=$datanum;$i++)
{
$content[$i-1] ="";
$mvauto=mysql_fetch_array($result,MYSQL_NUM);
for($j=0;$j<count($mvauto);$j++)
{$mvauto[$j]= iconv("gbk","utf-8",$mvauto[$j]);
if ($content[$i-1] != "")
{$content[$i-1]=$content[$i-1]."\t".$mvauto[$j];}
else {$content[$i-1]=$mvauto[$j];}
}
}
return $content;
mysql_close($id);
}
?>
