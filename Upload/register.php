<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���register.php
        * ˵�����û�ע��
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

/** ���غ��Ĳ��� */
require_once("width.php");

/** ��ʱ�ر�ע�� */
if($dyhb_options['allowed_register']=='0'){
    DyhbMessage($common_width[7],'index.php');
}

$UserId=intval( get_argget('id'));

/** ע���û���������û� */
 if($view=='add'){
	 /** ��ȡ������ */
	 $user_id=intval( get_argpost( 'user_id')) ;
	 $username=sql_check( get_argpost( 'username') );
	 $password =sql_check( get_argpost( 'password') );
	 $nikename =sql_check( get_argpost( 'nickname') );
	 $email =sql_check( get_argpost( 'email'));
	 $description =sql_check( get_argpost('description')) ;
	 $code =sql_check( get_argpost( 'code') );

	 /** �����ݽ��б�Ҫ���ж� */
     if($email){
		 dyhb_email($email);
	 }
	 if(!CheckPermission('nospam','������һ����֤��','0')){
	 if(!($code==$_SESSION['code']||$code==strtolower($_SESSION['code']))&&$dyhb_options[register_code]=='1'){
		  $url=GbkToUtf8( $_SERVER['HTTP_REFERER'],'');
		  Dyhbmessage("<font color=\"red\"><b>$common_width[8]</b></font>",$url);
	 }
	 }
     session_destroy();
	 /** ������û� */
	if($password==''){
		    DyhbMessage($common_width[9],'');
	  }
	if($R=$DB->getonerow("select *from `".DB_PREFIX."user` where `username`='$username'")){
            DyhbMessage($common_width[10],'');
	}elseif($email!=''&&$R=$DB->getonerow("select *from `".DB_PREFIX."user` where `email`='$email'")){
            DyhbMessage($common_width[11],'');
	}else{
	   $password=md5($password);
	   /** ���û���Ϣд�����ݿ� */
	   $DB->query("insert into `".DB_PREFIX."user` (`username`,`password`,`nikename`,`usergroup`,`description`,`email`,`dateline`)                    value('$username','$password','$nikename','3','$description','$email','$localdate')");
   } 
	/** ���»����ļ� */
	CacheBlogger();
	/** ����COOKIE */
	//set_cookie('auth', authcode("$user_id\t$password\t3"), $login_life);
	/** �������ݿ��еĵ�½�Ự */
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