<?php
#$arr=array("test","aaa","bbb");
#arraytofile($arr,"test");
function arraytofile($arr,$filename)
{while($key=each($arr)){$content=$key["value"]."\n";
file_put_contents($filename,$content,FILE_APPEND);}
}
?>
