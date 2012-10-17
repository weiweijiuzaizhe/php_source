<?php

require_once("auto_type_fetch.php");

class autoTypeTetchTest{
	public function test_qq(){
	
	
		$auto_type_fetch = new autoTypeFetch();
		$auto_type_fetch->get_qq_107();
	}
	
	public function test_pcauto(){
		
		$auto_type_fetch = new autoTypeFetch();
		$auto_type_fetch->get_pcauto_102();
	}
	
	
	public function test_wangyi(){
		
		$auto_type_fetch = new autoTypeFetch();
		$auto_type_fetch->get_wangyi_118();
	}
}



  $auto_type_fetch_test = new autoTypeTetchTest();
  $auto_type_fetch_test->test_wangyi();

?>