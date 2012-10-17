<?php

require_once("../web_class/Autohome.php");

class Autohome_test{
	//测试得到某个车系
	public function get_series_test(){

		
		$fp = fopen("../web_page/autohome_index.htm","r");

		$total_string = "";
		while($temp_sting = fgets($fp)){
			$total_string = $total_string.$temp_sting;
		}

		$total_string = iconv('gbk','utf-8',$total_string);
		
		$autohome = new Autohome();
		
		var_dump($autohome->get_autohome_series($total_string));

		
		
	}
	
	public function get_autohome_grey_stop_sale_test(){
		$fp = fopen("../web_page/auto_stop_sale.htm","r");

		$total_string = "";
		while($temp_sting = fgets($fp)){
			$total_string = $total_string.$temp_sting;
		}

		$total_string = iconv('gbk','utf-8',$total_string);
		
		
		//echo($total_string);
		
		$autohome = new Autohome();
		
		var_dump($autohome->get_autohome_grey_stop_sale($total_string));
	}
	
	
public function get_autohome_onsale_test(){

		$fp = fopen("../web_page/autohome_ordinary.htm","r");

		$total_string = "";
		while($temp_sting = fgets($fp)){
			$total_string = $total_string.$temp_sting;
		}

		$total_string = iconv('gbk','utf-8',$total_string);
		
		
		
		$autohome = new Autohome();
		
		var_dump($autohome->get_autohome_onsale($total_string));

		
		
	}
	
	public function get_autohome_stop_sale_test(){

		$fp = fopen("../web_page/autohome_ordinary_2.htm","r");

		$total_string = "";
		while($temp_sting = fgets($fp)){
			$total_string = $total_string.$temp_sting;
		}

		$total_string = iconv('gbk','utf-8',$total_string);
		
		
		
		$autohome = new Autohome();
		
		var_dump($autohome->get_autohome_stop_sale($total_string));

		
		
	}
	
	
   public function get_autohome_ajax_parse_test(){

		$fp = fopen("../web_page/autohome_ajax_content.txt","r");

		$total_string = "";
		while($temp_sting = fgets($fp)){
			$total_string = $total_string.$temp_sting;
		}

		//$total_string = iconv('gbk','utf-8',$total_string);
		
		
		
		$autohome = new Autohome();
		
		var_dump($autohome->get_autohome_ajax_parse($total_string));

		
		
	}
	
	
}


		$autohome_test = new Autohome_test();
		$autohome_test->get_series_test();


?>