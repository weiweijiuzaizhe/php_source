<?php
#$arr1=array("1\t2\t3\t6","3\t4\t5\t8","5\t6\t7\t20");
#$arr2=column_del($arr1,1);
#while($key=each($arr1)){echo $key["value"]."\n";}

function column_del($arr,$colnum)
{
$count=0;
$result=array();
while($key=each($arr)){
$col_arr=explode("\t",$key["value"]);
for($i=$colnum;$i<count($col_arr);$i++)
{$col_arr[$i-1]=$col_arr[$i];}
array_pop($col_arr);
$result[$count]=implode("\t",$col_arr);
$count+=1;
}
return $result;
}
?>
