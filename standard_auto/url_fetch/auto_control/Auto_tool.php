<?php
/*
 * 对于汽车训练的一些有用的杂七杂八的函数
 */
require_once "MyDbClass.php";

class Auto_class{
	
	
	

	//得到两个字符串的最长公共子串
	function max_long_str($str1,$str2){
		$str1 = strtolower($str1);
		$str2 = strtolower($str2);
		
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
		$str1 = strtolower($str1);
		$str2 = strtolower($str2);
		
		$offset_one = 0;//$str1的当前下标
		$offset_two = 0;//$str2的当前下标
		$length_one = mb_strlen($str1,'utf-8');
		$length_two = mb_strlen($str2,'utf-8');
		$str_to_return = "";
		for($j = $offset_one; $j <= $length_one;$j++){
			$str1_current_char  = iconv_substr($str1, $j,1,"UTF-8");
			for( $i = $offset_two  ;$i <= $length_two; $i++){
				$str2_current_char = iconv_substr($str2,$i,1,"UTF-8" );
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


	/*
	 * 得到所有已知汽车车系
	 */

	function get_total_auto_series_name(){
		
		$sql = "select ID,AutoBrandCHN,AutoModeCHN from mediavolap.Auto_Dim_AutoMode;";
		$mydb = new MyDbClass();
		$res = $mydb->db6_get_data_array($sql);
		
		return $res ;
		
		
	}
	
	
	/*
	 * 对于给定的汽车车系名称名称，给出在mediavolap.Auto_Dim_AutoMode中最接近前n款的车系
	 */
	function get_nearest_name($auto_series_input,$top_n){
		
		
		$auto_series_input = trim($auto_series_input);
		$auto_series_input = str_replace("-", "", $auto_series_input);
		
		$sql = "select ID,AutoBrandCHN,AutoModeCHN from mediavolap.Auto_Dim_AutoMode where mark = 0;";
		$mydb = new MyDbClass();
		$total_auto_array = $mydb->db6_get_data_array($sql);
		
		$array_to_return =  array();
		
		foreach($total_auto_array as $key => $value){
			$auto_series_name = $value[ "AutoModeCHN" ];
			$auto_series_brand = $value[ "AutoBrandCHN" ];
			$auto_series_id = $value[ "ID" ];
			$common_str1 = $this->common_ordered_chars($auto_series_input, $auto_series_name) ;
			$common_str_cnt = mb_strlen($common_str1,"UTF-8");
			
			if($common_str_cnt > 0){//有匹配的字段
				$value["common_cnt"] = $common_str_cnt;
				$array_to_return[] = $value;
			}
		}
		$to_sort_key = array();
		foreach($array_to_return as $key=>$value){
			$to_sort_key[$key] = $value["common_cnt"];
		}
		array_multisort($to_sort_key,SORT_DESC,$array_to_return);
		return array_slice($array_to_return,0,$top_n);
	}
	
	


}


$auto = new Auto_class();
var_dump($auto->get_nearest_name("奥迪",2));


//

//$str = strtolower("雷克萨斯lfxH");
//echo("$str\n");

?>