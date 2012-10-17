<?php
require_once("Autohome.php");

class Autohome_online
/*
 * 这个类就是autohome的在线上的类
 */
{
	
	public function get_series_change($total_string){//autohome首页的内容
		
		$autohome = new Autohome();
		$series_res = $autohome->get_autohome_series($total_string);
		
		$series_id_array = $series_res[ 1 ];
		$info_array = $series_res[ 'info' ];
		
		foreach($series_id_array as $key => $value){
			$SiteAutoModeID = $value;
			
			$sql = "select AutoModeID,AutoModeCHN,AutoBrandCHN from  mediavolap.Auto_Fact_AutoMode_AutoSite ".
			"where SiteID = 578 and SiteAutoModeID = $SiteAutoModeID; \n";
			
			echo($sql);
			
		}
		
		
	}
	
	
}





        $fp = fopen("autohome_index.htm","r");

		$total_string = "";
		while($temp_sting = fgets($fp)){
			$total_string = $total_string.$temp_sting;
		}

		$total_string = iconv('gbk','utf-8',$total_string);
		
		
		$autohome_online = new Autohome_online();
		$autohome_online->get_series_change($total_string);
		
		
		
		
		
?>