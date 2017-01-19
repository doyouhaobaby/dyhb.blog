<?php
function notice(){
	global $array_notice,$_COOKIE,$common_func;
	$n_c=get_cookie('commentname')?get_cookie('commentname'):$common_func[84];
	$i = mt_rand(0, count($array_notice) - 1);
	$notice = $array_notice[$i];	
	echo "{$common_func[85]}<font color=red>",$n_c,"</font>£¬",$notice;
}
?>