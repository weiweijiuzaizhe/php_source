<?php
function pattern_match($content,$patn)
{
$pattern = '|'.$patn.'|isU';
preg_match_all ($pattern, $content, $matches);
return $matches;
}
?>
