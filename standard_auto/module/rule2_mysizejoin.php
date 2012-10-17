<?php
function rule2_mysizejoin($arr1,$cn1,$arr2,$cn2,$joinorright)
{
$join1=array();
$count=0;
$join_right=array();
$count_r=0;

 foreach ($arr2 as $v2)
{
$status=FALSE;
$v2_arr=explode("\t",$v2);
$joincol2=$v2_arr[$cn2-1];//1*1*1:5,2*2*2:2;4*1*1:6
$joincol2_1=explode(",",$joincol2);//1*1*1:5  2*2*2:2  4*1*1:6
foreach($joincol2_1 as $jc2_1)
  {$joincol2_1_1=explode(":",$jc2_1);
$joincol2_1_final=$joincol2_1_1[0];
foreach ($arr1 as $v1) {
$v1_arr=explode("\t",$v1);
$joincol1=$v1_arr[$cn1-1];
$joincol1_1=explode(",",$joincol1);//1*1*1:5  2*2*2:2  4*1*1:6
       foreach($joincol1_1 as $jc1_1)
       {$joincol1_1_1=explode(":",$jc1_1);
$joincol1_1_final=$joincol1_1_1[0];


if($joincol1_1_final==$joincol2_1_final) { $join1[$count]=$v1."\t".$v2; $count+=1;$status=TRUE;}
       }
                      }
  }
if($status==FALSE){$join_right[$count_r]=$v2; $count_r+=1;}
}
$join1=array_unique($join1);
$join_right=array_unique($join_right);
switch ($joinorright){
case "join": return $join1; break;
case "right": return $join_right;break;
}
}

?>
