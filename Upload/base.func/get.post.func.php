<?php
//�Ա���ͳһ����
function get_args($name)
{
	if(isset($_POST[$name]))return trim($_POST[$name]);
	if(isset($_GET[$name]))return trim($_GET[$name]);
	return null;
}

//get��������
function get_argget($name){
	if(isset($_GET[$name]))return trim($_GET[$name]);
	return null;
}

//post��������
function get_argpost($name){
	if(isset($_POST[$name]))return trim($_POST[$name]);
	return null;
}
?>
