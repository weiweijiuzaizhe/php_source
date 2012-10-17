<?php
 
function read() { 
$fp = fopen('php://stdin', 'r'); 
$input = fgets($fp, 255); 
fclose($fp); 
//$input = chop($input); // 去除尾部空白 
return $input; 
} 

/*
print("What is your first name? "); 
$first_name = read(); 
print("What is your last name? "); 
$last_name = read(); 
print("Hello, $first_name $last_name! Nice to meet you!"); 
*/

/*
echo "wait: ";
$fp = fopen('php://stdin', 'r');
$line = fgets($fp, 512);
fclose($fp);

echo "$line\n";
echo "work......";
*/
$i = 0;
while($str != "quit\n"){ 
echo("$i\t请输入:");	
$str = fread(STDIN, 1000);
if($str == "\n"){
	$str = fread(STDIN, 1000);
}
echo "$i\t[STDIN]你输入的是".$str;  
$i++;
}

?> 