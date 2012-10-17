<?php

//得到两个字符串的最长公共子串
function max_long_str($str1,$str2) {
     $s_str=strlen($str1)<=strlen($str2)?$str1:$str2;//判断长短,用短的字串折半
     $l_str=strlen($str1)<=strlen($str2)?$str2:$str1;//折半后和长的字串来比较查找    
     for($j=strlen($s_str);$j>1;$j=ceil($j/2)) {//二分循环
         for($i=0;$i<strlen($s_str)-$j+1;$i++) {//对折半后长度的字串依次比较
             $temp=substr($s_str,$i,$j);
             if(($pos=strpos($l_str,$temp))!==FALSE) {
                 if((($pos-$b_pos)==1)||!isset($b_pos)) {
                     $str[$n]=substr_replace($str[$n],$temp,$k);//合并相邻
                     $k++;
                     $b_pos=$pos;
                 }else{$k=0;$n++;unset($b_pos);}
             }else{$k=0;$n++;unset($b_pos);};
         }
         if(isset($str)) {//有符合则不必再折半
             break;
         }
     }
     if(count($str)>=1) {//取长的
         foreach($str as $value){
                 $strr=strlen($value)>=strlen($strr)?$value:$strr;
             }
     }    
     return $strr;
}
/*
 * 得到两个字符串的同样顺序的所用公共字符
 */
function common_ordered_chars($str1,$str2){
	$offset_one = 0;//$str1的当前下标
	$offset_two = 0;//$str2的当前下标
	$length_one = mb_strlen($str1,'utf-8');
	$length_two = mb_strlen($str2,'utf-8');
	$str_to_return = "";
	for($j = $offset_one; $j <= $length_one;$j++){
		$str1_current_char  = substr($str1, $j,1);
		for( $i = $offset_two  ;$i <= $length_two; $i++){
			$str2_current_char = substr($str2,$i,1 );
			if($str1_current_char == $str2_current_char){//这是一个公共的字符
				$str_to_return = $str_to_return.$str1_current_char;
				$offset_two = $i + 1;
				$offset_one = $j + 1;
				break;
			}else{//不是公共的字符
			}
		}
	}
	return $str_to_return;
}




$str1 = "aaaaabccbbbbccccc";
$str2 = "aaaaccb";

$common_str = common_ordered_chars($str1, $str2);

echo("$common_str\n");

$common_str = common_ordered_chars($str2, $str1);

echo("$common_str\n");

?>