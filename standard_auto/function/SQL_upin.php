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
$excu=mysql_query($query,$id);
if($excu)
{ echo $sql."sucess!"."\n";}
else{
echo $sql."failed".":";
echo mysql_error();
}
mysql_close($id);
}
?>
