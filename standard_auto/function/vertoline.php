<?php
/*require("/home/lijue/spider/autocar/standard_auto/function/filetoarray.php");
$a=filetoarray("sohu_automode.txt");
$b=vertoline($a);
while($kev=each($b)){echo $kev['value']."\n";};
*/
function vertoline($arr)
{$key=array();$content=array();$merge=array();$j=-1;
for($i=0;$i<count($arr);$i++)
{
$arrstr[$i]=explode("\t",$arr[$i]);//二维数组行数+列数
if($arrstr[$i][1]==$key[$j])//选择，作为id的列值
{
for($m=12;$m<18;$m++){
$content[$m][$j]=$content[$m][$j].",".$arrstr[$i][$m];}

}//选择，要合并的列赋值
else
{$j+=1;$key[$j]=$arrstr[$i][1];//作为id的列植
for($m=1;$m<count($arrstr[$i]);$m++)
{
$content[$m][$j]=$arrstr[$i][$m];
$content[$m][$j]=$arrstr[$i][$m];
}
}//不合并的列赋值
}
for($j=0;$j<count($key);$j++)
{$merge[$j]=$key[$j];
for($m=1;$m<count($arrstr[0]);$m++)
{$merge[$j]=$merge[$j]."\t".$content[$m][$j];
}
}
return $merge;
}

?>
