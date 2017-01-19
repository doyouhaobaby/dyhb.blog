<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：function.safe.php
        * 说明：安全函数
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

/**
 * is the user is admin
 *
 * @return boolean
 */
function CheckLogin(){
	global $dyhb_userid,$dyhb_password,$dyhb_logincount,$dyhb_hash,$DB;
    if (!$dyhb_userid || !$dyhb_password|| !$dyhb_logincount || !$dyhb_hash) {
	     header("location:../login.php");
    } else {
	    $r = $DB->getonerow("SELECT user_id, password, logincount FROM ".DB_PREFIX."user WHERE user_id='$dyhb_userid'");
	    if(!$r) {
		     header("location:../login.php");
	    }
	    if($dyhb_password != $r['password']) {
		     header("location:../login.php");
	    }
	    if($dyhb_logincount != $r['logincount']) {
		    header("location:../login.php");
	}
  }
}

/**
 * 退出登陆
 *
 * @return boolean
 */
function LoginOut(){
   global $dyhb_usergroup,$dyhb_userid,$dyhb_username,$dyhb_password;
	   replacesession();
	   dcookies();
	   $dyhb_usergroup = 0;
	   $dyhb_userid = 0;
	   $dyhb_username = $dyhb_password = '';
       header("location:./");
}

/**
 * 站点登录,注册
 *
 * @return string
 */

//登录表单
function _login_html(){
	global $dyhb_options,$common_func;
	$code=$dyhb_options[admin_code]=='1'?EchoCode():'';
	return <<<DYHB
<div class="blog_login">
<form action="login.php" method="post">
<p><label for="username">$common_func[64]</label><input type='text' name='username'></p>
<p><label for="password">$common_func[65]</label><input type='password' name='password'></p>
<p>$code</p>
<p><input type="submit" name="ok" value='$common_func[66]' ></p>
</form>
<p><a href='index.php'>$common_func[67]</a> | <a href='public.php'>$common_func[68]</a></p>
</div>
DYHB;
}

//注册表单
function _register_html(){
   global $dyhb_options,$common_func;
   //基本处理
   $code=$dyhb_options[register_code]=='1'?EchoCode():'';
   return <<<DYHB
<div class="blog_register">
<form action="register.php?do=add" method="post">
<p><label for="username">$common_func[64]<label><input type='text' name='username' value=""></p>
<p><label for="password">$common_func[65]<label></label><input type='password' name='password' value=""></p>
<p><label for="coolname">$common_func[69]<label><input type='text' name='coolname' value=""></p>
<p><label for="email">$common_func[70]<label><input type='text' name='email' value=""></p>
<p><label for="description">$common_func[71]<input type='text' name='description' value=""></p>
<p>$code</p>
<p><input type="submit" name="ok" value='$common_func[72]' ></p>
</form>
<p><a href='index.php'>$common_func[67]</a> | <a href='public.php'>$common_func[68]</a></p>
</div>
DYHB;
}

//快速发布日志表单
function _quicklog_html(){
   global $_globalTreeSorts,$common_func;
   //基本处理
   $code=$dyhb_options[register_code]=='1'?EchoCode():'';
   $back= "<div class=\"blog_quicklog\"><ul>";
   //获取分类数据
   if($_globalTreeSorts){foreach($_globalTreeSorts as $value){
	    $theurl1="index.php?s=$value[sort_id]&way=quicklog";
	    $back.="<li>|--<a href='$theurl1'>{$value[name]}</a></li>";
		    if($value[child]){foreach($value[child] as $val){
				 $theurl2="index.php?s=$val[sort_id]&way=quicklog";
				 $back.="<li>&nbsp;&nbsp;&nbsp;&nbsp;|----<a href='$theurl2'>{$val[name]}</a></li>";
				       if($val[child]){foreach($val[child] as $val2){
						   $theurl3="index.php?s=$val2[sort_id]&way=quicklog";
						   $back.="<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|----<a href='$theurl3'>{$val2[name]}</a></li>";
                       }}               
                 }}
   }}
   $back.="</ul><p><a href='index.php'>$common_func[67]</a> | <a href='public.php'>$common_func[68]</a></p></div>";
   return $back;
}

/**
 * 权限检查
 *
 * 权限名字
 * @return  string(errer message back)
 */
function CheckPermission($permission_name,$message,$type='') {
	global $dyhb_premission,$dyhb_userid,$dyhb_usergroup,$dyhb_options,$common_func;
	//超级管理员不限制其权限
	if($dyhb_userid!='1'){
	  if ($dyhb_premission[$permission_name]!=1) {
	  	if($type!='0'){
	  		DyhbMessage($message."$common_func[73]({$dyhb_premission[gpname]})$common_func[74]<a href='$dyhb_options[blogurl]?action=usergroup&id={$dyhb_usergroup}'>$common_func[75]</a>",'0');
	  	}else{
	  		return false;
	  	}
	  }else{
	     return true;
	  }
	}else{
	    return true;
	}
}
/**
 *
 * 是否超级管理员
 */
function IsSuperAdmin($message,$type='') {
	global $dyhb_userid,$dyhb_premission,$dyhb_usergroup,$dyhb_options,$common_func;
    if($dyhb_userid!='1'){
    	if($type!='0'){
    		DyhbMessage($message."$common_func[73]($dyhb_premission[gpname])$common_func[76]<br><a href='$dyhb_options[blogurl]?action=usergroup&id={$dyhb_usergroup}'>$common_func[75]</a>",'0');
    	}else{
    		return false;
    	}
    }else{
    	return true;
    }
}


/**
 *
 * 验证码
 */
function EchoCode(){
   global $dyhb_premission,$common_func;
   $code='';
   if(!CheckPermission('nospam',$common_func[78],'0')){
      $code=<<<DYHB
         验证码：<input type='text' name='code' size='10' class="input">&nbsp;
        <img src='includes/code.php' onClick="this.src='includes/code.php?rand='+Math.random();" title="$common_func[78]">
DYHB;
   }
   return $code;
}

/**
 * sql safe
 *
 * @param string $sql
 * @return boolean
 */
function CheckSql($sql){
   global $common_func;
   $result=eregi('select|insert|update|delete|table|union|from|where|\'|\/\*|\*|\.\.\/|\.\/|union|load_file|outfile',$sql);
   if($result){
       DyhbMessage("<font color='red'>$common_func[79]</font>");
   }else{
     return $sql;
   }
}


/**
 * 生成随即数
 *
 * @param int $length
 * @return boolean
 */
function random($length, $numeric = 0) {
	PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
	if($numeric) {
		$hash = sprintf('%0'.$length.'d', mt_rand(1, pow(10, $length) - 1));
	} else {
		$hash = '';
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
		$max = getstrlen($chars) - 1;
		for($i = 0; $i < $length; $i++) {
			$hash .= $chars[mt_rand(0, $max)];
		}
	}
	return $hash;
}


/**
 * base64编码函数
 *
 * @param string $string
 * @return boolean
 */
function authcode($string, $operation = 'ENCODE') {
	$string = $operation == 'DECODE' ? base64_decode($string) : base64_encode($string);
	return $string;
}

/**
 * 取得字符串的长度，包括中英文
 *
 * @param string $text
 * @return string
 */
function getstrlen($text){
	if (function_exists('mb_substr')) {
		$length=mb_strlen($text,'UTF-8');
	} elseif (function_exists('iconv_substr')) {
		$length=iconv_strlen($text,'UTF-8');
	} else {
		preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $text, $ar);
		$length=count($ar[0]);
	}
	return $length;
}

/**
 * 去除转义字符
 *
 * @param string $string
 * @return string
 */
function dyhb_stripslashes($string){
  if(is_array($string)){
	foreach($string as $key=>$value){
		$string[$key]=dyhb_stripslashes($value);
	}
   }else{
	if(is_string($string)){
		$string=stripslashes($string);
	 }
	}
	return $string;
}

/**
 * 添加转义字符
 *
 * @param string $string
 * @return string
 */
function dyhb_addslashes($string){
   if(is_array($string)){
	foreach($string as $key=>$value){
		$string[$key]=dyhb_addslashes($value);
	}} else {
	if(is_string($string)){
		$string=addslashes($string);
	}}
    return $string;
}

/**
 * 转换字符
 *
 * @param string $string
 * @return string
 */
function char_html($string){
  $string = htmlspecialchars(dyhb_addslashes($string));
  return $string;
}

/**
 * 清除HTML代码
 *
 * @param string $string
 * @return string
 */
function html_clean($string) {
  $html=array('<p>','</p>','<img>','</img>');
  //$string = htmlspecialchars($string);
 $string = str_replace("\n", "<br />", $string);
 $string = str_replace("  ", "&nbsp;&nbsp;", $string);
 $string = str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;", $string);
 foreach($html as $value){
	$string = str_replace("",$value, $string);
  }
 return $string;
}

/**
 * 输出还原html代码
 *
 * @param string $string
 * @return string
 */
function unhtmlspecialchars($string){
  if(empty($string)) {
              return '';
   } else {
       $string=str_replace('&nbsp;',' ',$string);
       $string=str_replace('"','"',$string);
       $string=str_replace('&amp;','&',$string);
       $string=str_replace('>','>',$string);
       $string=str_replace('<','<',$string);
	   $string=str_replace('”','"',$string);
       return $string;
   }
}

/**
 * HTML转换为纯文本
 *
 * @param string $content
 * @return string
 */
function html2text($content) {
	$content = preg_replace("/<style .*?<\/style>/is", "", $content);
	$content = preg_replace("/<script .*?<\/script>/is", "", $content);
	$content = preg_replace("/<br\s*\/?>/i", "\n", $content);
	$content = preg_replace("/<\/?p>/i", "\n", $content);
	$content = preg_replace("/<\/?td>/i", "\n", $content);
	$content = preg_replace("/<\/?div>/i", "\n", $content);
	$content = preg_replace("/<\/?blockquote>/i", "\n", $content);
	$content = preg_replace("/<\/?li>/i", "\n", $content);
	$content = strip_tags($content);
	$content = preg_replace("/\&\#.*?\;/i", "", $content);
	return $content;
}

/**
 * post,get对象过滤通用函数
 *
 * @return string
 */
function login_check($post){
   $MaxSlen=30;//限制登陆验证输入项最多20个字符
   if (!get_magic_quotes_gpc())    // 判断magic_quotes_gpc是否为打开
   {
      $post=addslashes($post);    // 进行magic_quotes_gpc没有打开的情况对提交数据的过滤
   }
   $post = LenLimit($post,$MaxSlen);
   $post=preg_replace("/　+/","",trim(str_replace(" ","",$post)));
   $post=cleanHex($post);
   if (strpos($post,"=")||strpos($post,"'")||strpos($post,"\\")||strpos($post,"*")||strpos($post,"#")){
       return false;
   }else{
       return true;
   }
}
function sql_check($str)
{
   $MaxSlen=300;//限制短输入项最多300个字符
   if (!get_magic_quotes_gpc())    // 判断magic_quotes_gpc是否打开
   {
      $str = addslashes($str);    // 进行过滤
   }
		$str = LenLimit($str,$MaxSlen);
		$str = str_replace(array("\'","\\","#"),"",$str);
		$str= htmlspecialchars($str);
		return preg_replace("/　+/","",trim($str));
}

/**
 * 限制输入字符长度，防止缓冲区溢出攻击
 *
 * @return string
 */
function LenLimit($Str,$MaxSlen){
    if(isset($Str{$MaxSlen})){
        return " ";
    }else{
        return $Str;
    }
}

/**
 * sql方面过滤
 *
 * @return string
 */
function filt_num_array($id_str){
	if($id_str!=''){
		$id_array=array_map("intval",explode(",",$id_str));
		$id_str=join(",",$id_array);
		return $id_str;
	}else{
		return 0;
	}
}

function filt_str_array($str){
	$gstr="";
	$str_array=array_map("sql_filter",explode(",",$str));
	foreach($str_array as $val){
		if($val!=''){
			$gstr.="'".$val."',";
		}
	}
	$gstr=preg_replace("/,$/","",$gstr);
	$str_array=explode(",",$gstr);
	$str=join(",",$str_array);
	return $str;
}


function sql_filter($str){
	return str_replace(array("/","\\","'","#"," ","  ","%","&","(",")"),"",$str);
}

function filt_fields($fields){
	if(strstr($fields,",")){
		$fields_array=array_map("sql_filter",explode(",",$fields));
		$fields_str=join(",",$fields_array);
	}else{
		$fields_str=sql_filter($fields);
	}
	return $fields_str;
}

function check_item($check_str,$match_array){
	$result_str='';
	$check_array=explode(",",$check_str);
	foreach($check_array as $rs){
		if(in_array($rs,$match_array)){
			$result_str.=$rs.",";
		}
	}
	return preg_replace("/,$/","",$result_str);
}

?>