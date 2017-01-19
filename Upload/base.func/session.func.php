<?php

//设置COOKIE
function get_cookie($var) {
	$var = SESSION_PREFIX.$var;
	return isset($_COOKIE[$var]) ? $_COOKIE[$var] : False;
}

//获取COOKIE
function set_cookie($name,$var,$time) {
	global $localdate;
	$name = SESSION_PREFIX.$name;
	setcookie($name,$var,$localdate+$time);
	return true;
}

//设置session
function set_session($k,$v){
	if(is_NULL($v)||$v==''){
		unset($_SESSION[SESSION_PREFIX.$k]);
	}
	else{
		$_SESSION[SESSION_PREFIX.$k] = $v;
	}
}
//获取session
function get_session($k){
	if(isset($_SESSION[SESSION_PREFIX.$k])) return $_SESSION[SESSION_PREFIX.$k];
	return null;
}

// 删除cookies
function dcookies($key = '') {
	global $dyhb_userid, $dyhb_username,$dyhb_password,$localdate;
	if ($key) {
		if(is_array($key)) {
			foreach ($key as $name) {
				setcookie($name, '',$localdate-3600);
			}
		} else {
			setcookie($key, '',$localdate-3600);
	    }
	} else {
		if(is_array($_COOKIE)) {
			foreach ($_COOKIE as $key => $val) {
				setcookie($key,'',$localdate - 3600);
			}
		}
		$dyhb_userid = 0;
		$dyhb_username = $dyhb_password = '';
	}
}

//更新登陆session
function updatesession() {
	if(!empty($GLOBALS['sessionupdated'])) {
		return true;
	}
	global $DB, $sessionexists, $sessionupdated, $dyhb_hash,$dyhb_userid, $dyhb_usergroup, $localdate, $dyhb_seccode, $dyhb_auth_key;
	if($sessionexists == 1) {
		$DB->query("UPDATE ".DB_PREFIX."session SET user_id='$dyhb_userid', usergroup='$dyhb_usergroup', seccode='$dyhb_seccode' WHERE hash='$dyhb_hash'");
	} else {
		replacesession(1);
	}
	$sessionupdated = 1;
}

//重置session
function replacesession($insert = 0) {
	global $DB, $dyhb_hash, $dyhb_userid, $dyhb_usergroup, $localdate, $dyhb_seccode, $dyhb_auth_key;
	$DB->query("DELETE FROM ".DB_PREFIX."session WHERE hash='$dyhb_hash' OR ('$dyhb_userid'<>'0' AND user_id='$dyhb_userid')  OR (user_id='0')");
	if ($insert) {
		$DB->query("INSERT INTO ".DB_PREFIX."session (hash, auth_key, user_id, usergroup, seccode) VALUES ('$dyhb_hash', '$dyhb_auth_key', '$dyhb_userid', '$dyhb_usergroup', '$dyhb_seccode')");
	}
}

//ob_start
function obstart() {
	if ( $dyhb_options['gzipcompress'] && function_exists('ob_gzhandler') ) {
		ob_start('ob_gzhandler');
	} else {
		ob_start();
	}
}
//ob_end_clean
function obclean() {
	ob_end_clean();
	obstart();
}

?>