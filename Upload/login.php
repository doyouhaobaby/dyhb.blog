<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���login.php
        * ˵������¼��֤
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

/** ��½�жϣ�����Ѿ���½���Զ���ת����̨��ֻ�й���Ա������׫д�߲Ż��½����̨ */
if(ISLOGIN){
    header("location:admin");
}
if($_POST['ok']){
    $username = sql_check( get_argpost( 'username') );
    $password = md5(sql_check( get_argpost( 'password') ));

    $code = sql_check( get_argpost( 'code') );
	if(!CheckPermission('nospam','������һ����֤��','0')){
    if(!($code==$_SESSION['code']||$code==strtolower($_SESSION['code']))&&$dyhb_options[admin_code]=='1'){
		$url=GbkToUtf8( $_SERVER['HTTP_REFERER'],'');
		Dyhbmessage("<font color=\"red\"><b>$common_width[8]</b></font>",$url);
	}
	}
    session_destroy();
	
   if($login_userinfo=$DB->getonerow("select *from `".DB_PREFIX."user` where `username`='$username' and `password`='$password'")){
        /** ���µ�½��������½ʱ��͵�½IP */
		$DB->query("UPDATE ".DB_PREFIX."user SET logincount=logincount+1, logintime='$localdate', loginip='$dyhb_onlineip' WHERE user_id='$login_userinfo[user_id]'");
		$logincount = $login_userinfo['logincount']+1;
		$dyhb_userid = $login_userinfo['user_id'];
		$dyhb_usergroup=$login_userinfo['usergroup'];

		/** ����cookie */
		set_cookie('auth',authcode("$dyhb_userid\t$password\t$logincount"),$login_life);
		/** �������ݿ��еĵ�½�Ự */
		updatesession();
		if($dyhb_options[after_login_back]=='3'){
		     $url=GbkToUtf8( $_SERVER['HTTP_REFERER'],'');
		}elseif($dyhb_options[after_login_back]=='2'){
		     $url="index.php";
		}else{
		     $url="admin";
		}
		header("location:{$url}");
   }else {
	   DyhbMessage("<font color='red'><b>{$common_width[41]}</b></font>",'');
   } 
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
   <head>
      <title><?php echo $common_width[39];?></title>
      <meta http-equiv="content-type" content="application/xhtml+xml; charset=gbk" />
      <link rel="stylesheet" href="images/common.css" type="text/css" />
   </head>
   <body>
      <table align="center">
        <tr>
          <td>
             <div id="main">
			      <h1><?php echo $common_width[39];?></h1>
                 <p><?php echo $common_width[40];?></p>
				  <?php echo _login_html();?>
             <div>
          </td>
        </tr>
       </table>
    </body>
</html>