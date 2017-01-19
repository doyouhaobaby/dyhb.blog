<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：register.php
        * 说明：用户注册
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

/** 加载核心部件 */
require_once("width.php");

/** 随时关闭注册 */
if($dyhb_options['allowed_register']=='0'){
    DyhbMessage($common_width[7],'index.php');
}

$UserId=intval( get_argget('id'));

/** 注册用户，添加新用户 */
 if($view=='add'){
	 /** 获取表单数据 */
	 $user_id=intval( get_argpost( 'user_id')) ;
	 $username=sql_check( get_argpost( 'username') );
	 $password =sql_check( get_argpost( 'password') );
	 $nikename =sql_check( get_argpost( 'nickname') );
	 $email =sql_check( get_argpost( 'email'));
	 $description =sql_check( get_argpost('description')) ;
	 $code =sql_check( get_argpost( 'code') );

	 /** 对数据进行必要的判断 */
     if($email){
		 dyhb_email($email);
	 }
	 if(!CheckPermission('nospam','免输入一切验证码','0')){
	 if(!($code==$_SESSION['code']||$code==strtolower($_SESSION['code']))&&$dyhb_options[register_code]=='1'){
		  $url=GbkToUtf8( $_SERVER['HTTP_REFERER'],'');
		  Dyhbmessage("<font color=\"red\"><b>$common_width[8]</b></font>",$url);
	 }
	 }
     session_destroy();
	 /** 添加新用户 */
	if($password==''){
		    DyhbMessage($common_width[9],'');
	  }
	if($R=$DB->getonerow("select *from `".DB_PREFIX."user` where `username`='$username'")){
            DyhbMessage($common_width[10],'');
	}elseif($email!=''&&$R=$DB->getonerow("select *from `".DB_PREFIX."user` where `email`='$email'")){
            DyhbMessage($common_width[11],'');
	}else{
	   $password=md5($password);
	   /** 将用户信息写入数据库 */
	   $DB->query("insert into `".DB_PREFIX."user` (`username`,`password`,`nikename`,`usergroup`,`description`,`email`,`dateline`)                    value('$username','$password','$nikename','3','$description','$email','$localdate')");
   } 
	/** 更新缓存文件 */
	CacheBlogger();
	/** 保存COOKIE */
	//set_cookie('auth', authcode("$user_id\t$password\t3"), $login_life);
	/** 更新数据库中的登陆会话 */
	//updatesession();
	DyhbMessage($common_width[12],'login.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
      <head>
         <title><?php echo $common_width[13];?></title>
         <meta http-equiv="content-type" content="application/xhtml+xml; charset=gbk" />
         <link rel="stylesheet" href="images/common.css" type="text/css" />
      </head>
      <body>
          <div id="main">
		      <h1><?php echo $common_width[14];?></h1>
              <p><?php echo $common_width[15];?></p>
			  <?php
			     if(ISLOGIN)  echo "<p>".$_USERINFOR['dyhb_username']."$common_width[16]</p>";
                 echo _register_html();
			  ?>
            <div>
       </body>
</html>