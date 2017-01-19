<?php
//对表单的统一处理
function get_args($name)
{
	if(isset($_POST[$name]))return trim($_POST[$name]);
	if(isset($_GET[$name]))return trim($_GET[$name]);
	return null;
}

//get参数处理
function get_argget($name){
	if(isset($_GET[$name]))return trim($_GET[$name]);
	return null;
}

//post参数处理
function get_argpost($name){
	if(isset($_POST[$name]))return trim($_POST[$name]);
	return null;
}
?>
