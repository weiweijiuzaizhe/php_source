<?php
/*require("/home/lijue/spider/autocar/standard_auto/function/filetoarray.php");
require("/home/lijue/spider/autocar/standard_auto/function/arraytofile.php");
$a=filetoarray("sohu_automode.txt");
$b=mysort($a);
$c=vertoline($b);
arraytofile($c,"sohu_rawdata.txt");
//while($kev=each($c)){echo $kev['value']."\n";};
*/

function mysort($a)
{
for($i=0;$i<count($a);$i++)
{
$b[$i]=explode("\t",$a[$i]);
$b_sort[$i]=$b[$i][0];//挖取排序列，0为第一列
}
asort($b_sort);
$j=0;
while ($kev=each($b_sort))
{$key= $kev['key'];
$result[$j]=$a[$key];//排序列排序后的索引关联原数组
$j+=1;}
return $result;
}

?>
