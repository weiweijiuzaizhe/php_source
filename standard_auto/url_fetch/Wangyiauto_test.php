<?php
require_once("web_class/Wangyiauto.php");


class Wangyiauto_test{

	//测试得到某个车系
	public function get_series_test(){

		$fp = fopen("web_page/wangyi_firstchar.htm","r");

		$total_string = "";
		while($temp_sting = fgets($fp)){
			$total_string = $total_string.$temp_sting;
		}

		$total_string = iconv('gbk','utf-8',$total_string);

		$wangyi_auto = new Wangyiauto();
		var_dump($wangyi_auto->get_wangyiauto_series($total_string));
	}


	public function get_wangyi_stop_series_test(){
		$fp = fopen("web_page/wangyi_series_2068.html","r");

		$total_string = "";
		while($temp_sting = fgets($fp)){
			$total_string = $total_string.$temp_sting;
		}

		$total_string = iconv('gbk','utf-8',$total_string);

		$wangyi_auto = new Wangyiauto();
		var_dump($wangyi_auto->get_wangyiauto_stop_series($total_string));
	}


	public function get_wangyiauto_onsale_test(){
		$fp = fopen("web_page/wangyi_series_2209.html","r");

		$total_string = "";
		while($temp_sting = fgets($fp)){
			$total_string = $total_string.$temp_sting;
		}

		$total_string = iconv('gbk','utf-8',$total_string);

		$wangyi_auto = new Wangyiauto();
		var_dump($wangyi_auto->get_wangyiauto_onsale($total_string));
	}

	public function get_wangyiauto_stop_produce_test(){
		$fp = fopen("web_page/wangyi_series_2209.html","r");

		$total_string = "";
		while($temp_sting = fgets($fp)){
			$total_string = $total_string.$temp_sting;
		}

		$total_string = iconv('gbk','utf-8',$total_string);

		$wangyi_auto = new Wangyiauto();
		var_dump($wangyi_auto->get_wangyiauto_stop_produce($total_string));
	}

}


$wangyiauto_test = new Wangyiauto_test();
$wangyiauto_test->get_wangyiauto_stop_produce_test();


?>