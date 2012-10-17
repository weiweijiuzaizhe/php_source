<?php
#$arr = array("2","3\t1543*1234*1542,1543*1234*1542,1543*1234*1542,");
#$ckg=conv_strcoltofrq($arr,2);
#while($kev=each($ckg)){ echo $kev['value'],"\n";}

function conv_strcoltofrq($arr,$col){
$ckg=array();$result=array();
$count=0;
while($arr_2=each($arr))//读取每行 
   {
    $v1=explode("\t",$arr_2['value']);//每行分裂
    $lwh = explode(",",$v1[$col-1]); //将字符串列分裂
    $ckgcount = array_count_values($lwh);//计算重复个数
    $count_c=0;
    while($kev=each($ckgcount))
    { if($kev['key']!=null) 
       {$ckg[$count_c]=$kev['key'].":".$kev['value'];$count_c+=1; }
    }
    $v1[$col-1]=implode(",",$ckg);
    $result[$count]=implode("\t",$v1);
    $count+=1;
unset($v1);
unset($lwh);
unset($ckg);
unset($ckgcount);  
 }
return $result;
}

?>
