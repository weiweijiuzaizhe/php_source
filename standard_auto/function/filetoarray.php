<?php
function filetoarray($filename){
$file = fopen($filename,"r") or exit("Unable to open file!");
$i=0;$file_arr=array();
while(!feof($file))
{ $file_arr[$i]=fgets($file);
$file_arr[$i]=trim($file_arr[$i]);
$i+=1;}
fclose($file);
array_pop($file_arr);
return $file_arr;
}
?>
