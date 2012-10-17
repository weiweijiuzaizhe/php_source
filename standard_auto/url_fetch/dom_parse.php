<?php

$html = new simple_html_dom();

$res = $html->load_file('http://price.pcauto.com.cn/sg919/');


var_dump($html);
?>