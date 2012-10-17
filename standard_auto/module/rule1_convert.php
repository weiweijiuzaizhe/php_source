<?php
#$arr=array("s\tD-d","b\ta A");
#$arr=rule1_convert($arr,2);
#while($key=each($arr)){echo $key["value"]."\n";}

function rule1_convert($arr,$col)
{
$count=0;
while($key=each($arr)){
$arr_2=explode("\t",$key["value"]);
$arr_2[$col-1]=strtolower($arr_2[$col-1]);
$arr_2[$col-1]=preg_replace("#( |-)#","",$arr_2[$col-1]);
$rarr[$count]=implode("\t",$arr_2);
$count+=1;
}
return $rarr;
}
?>
