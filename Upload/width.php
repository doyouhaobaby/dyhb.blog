<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���width.php
        * ˵����ȫ��/width/�����˴�
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

/** ������󱨸漶�����������־��棬��������Ϊ0 */
error_reporting(7);

/** ���忪ʼ,���� */
ob_start();

/** ����Ĭ�ϱ����ַ����룬��ʱֻ�������� */
header("content-Type: text/html; charset=GBK");

/** ҳ���ʱ����ʼ */
$Microtime = explode(' ', microtime());
$Starttime =$Microtime [1]+ $Microtime[0];

/** ��������·�� */
require_once("root.php");

/**  ����ȫ�������ļ� */
require_once(DOYOUHAOBABY_ROOT.'/includes/config.php');
require_once(DOYOUHAOBABY_ROOT.'/includes/dyhb.config.php');

/** ���غ�����,����ȫ�����ݴ��� */
foreach(array('gbk','html2ubb','img','ip','plugin','time','visitor','sql','log','cache.html','get.post','session','page','tpl','safe','base','notice','smarty','rewrite','model','digg','sendemail') as $val){
   $FuncPath=DOYOUHAOBABY_ROOT."/base.func/$val.func.php";
   if(file_exists($FuncPath)){
       require_once($FuncPath);
   }
}

/** session��½״̬��¼ */
require_once(DOYOUHAOBABY_ROOT.'/includes/a.session.php');

/** mysql���ݿ�װ�� */
require_once(DOYOUHAOBABY_ROOT.'/class.lib/class.mysql.php');
$DB = new Mysql (DB_HOST,DB_USER,DB_PASSWORD);
$DB->selectdb (DB_NAME);
$DB->setchar(DB_UNICODE);

/** �ؽ�����,�������ϵͳ��ʼ��ʱ����������ȫ������ */
if (!@ file_exists(DOYOUHAOBABY_ROOT.'/width/cache/c_dyhb_options.php')&&!@ file_exists(DOYOUHAOBABY_ROOT.'/install.php')) {
	 CacheOptions();
	 DyhbMessage("ȫվ���û����Ѿ�����������Ҫ��¼��̨����ȫվ���棡<br>The station configuration cache has been established, you need to admin control panel cache for the whole station!",'0');
}

/** ���ݿ������װ�࣬�����������ģ����Ϊ$_Logs=new Logs($DB) */
foreach(array('cools','logs','sorts','comments','tags','links','photosorts','mp3s','trackbacks') as $val){
    $Val=ucwords($val);
    $ClassValue='_'.$Val;
    require_once(DOYOUHAOBABY_ROOT."/class.lib/class.{$val}.php");
    if(!class_exists($Val)){
	       DyhbMessage($common_width[1].$Val,'0');
    }
    $$ClassValue=new $Val($DB);
}

/** ��ȡ����ȫ�����û��� */
$dyhb_options=ReadCache('dyhb_options');

/** ���ع������԰� */
/* ���Ĭ�����԰���ַ����ֹδ���ɻ��治�ܷ���վ�㣡 */
$dyhb_options[global_lang_select]=$dyhb_options[global_lang_select]?$dyhb_options[global_lang_select]:"zh_cn";
require_once(DOYOUHAOBABY_ROOT."/images/lang/$dyhb_options[global_lang_select]/notice.php");
require_once(DOYOUHAOBABY_ROOT."/images/lang/$dyhb_options[global_lang_select]/width.php");

/** ʹ�������ڵ�ʱ����������־�����۵ȵ���Ҫ�õ� */
$timezone  = intval($dyhb_options['timezone']);
$localdate = time() - ($timezone - 8) * 3600;

/** ��¼���û�,��½�û����� */

/** ע������½�û��Ƴ� */
if(isset($_GET['login_out'])&&$_GET['login_out']=="true"){
    LoginOut();
}

/** ����Ip,auth */
$dyhb_onlineip =  getIp();
$dyhb_auth_key = md5($dyhb_onlineip.$_SERVER['HTTP_USER_AGENT']);

/** ��ȡ���룬�û���Ϣ */
list($dyhb_userid, $dyhb_password, $dyhb_logincount) = get_cookie('auth') ? explode("\t", authcode(get_cookie('auth'), 'DECODE')) : array('', '', 0);
$dyhb_hash = dyhb_addslashes(get_cookie('hash'));
$dyhb_userid = intval($dyhb_userid);
$dyhb_password = dyhb_addslashes($dyhb_password);
$dyhb_logincount = intval($dyhb_logincount);

/** 1������Ա��2������׫д��3��ע���û���4����������Ա��5�ο� */
$dyhb_usergroup = 5;

/** �û���Ϣ���� */
$_USERINFOR = array();
$dyhb_seccode = $sessionexists = 0;

$userfields = 'u.user_id AS dyhb_userid, u.username AS dyhb_username, u.password AS dyhb_password, u.usergroup AS dyhb_usergroup, u.email as dyhb_email, u.homepage as dyhb_homepage, u.nikename AS dyhb_nikename,u.logincount AS dyhb_logincount';

if ($dyhb_hash) {
	if ($dyhb_userid) {
		$query ="SELECT s.hash, s.seccode, $userfields
			    FROM `".DB_PREFIX."user` u
			    LEFT JOIN ".DB_PREFIX."session s ON (s.user_id = u.user_id)
			    WHERE s.hash='$dyhb_hash' AND u.user_id='$dyhb_userid'
			    AND u.password='$dyhb_password' AND s.auth_key='$dyhb_auth_key'";
	}else {
		 $query ="SELECT hash,user_id as dyhb_userid,usergroup,seccode FROM ".DB_PREFIX."session WHERE hash='$dyhb_hash' LIMIT 1";
	}
	if ($_USERINFOR = $DB->getonerow($query)){
		$sessionexists = 1;
	    if($_USERINFOR['dyhb_userid']) {
			 $query = "SELECT $userfields FROM ".DB_PREFIX."user u WHERE u.user_id='".intval($_USERINFOR['dyhb_userid'])."'";
			 $_USERINFOR = array_merge($_USERINFOR,$_USERINFOR=$DB->getonerow($query));
			 $dyhb_userid = $_USERINFOR['user_id'];
		}
	 } else {
	       if($_USERINFOR = $DB->getonerow("SELECT hash,usergroup,seccode FROM ".DB_PREFIX."session WHERE hash='$dyhb_hash'")) {
			    dcookies();
			    $sessionexists = 1;
		  }
	 }
}

if(!$sessionexists) {
	if($dyhb_userid) {
		if(!($_USERINFOR = $DB->getonerow("SELECT $userfields FROM `".DB_PREFIX."user` u  WHERE u.user_id='$dyhb_userid' AND u.password='$dyhb_password'"))) {
			dcookies();
		 }
	 }
	 $_USERINFOR['dyhb_hash'] = random(6);
	 $_USERINFOR['dyhb_seccode'] = random(6, 1);
}
$userfields = '';
@extract($_USERINFOR);

if(!$dyhb_userid || !$dyhb_username) {
	 $dyhb_username = '';
	 $dyhb_usergroup = 5;
}

if ($dyhb_usergroup == 1) {
	 error_reporting(7);
}

if(!get_cookie('hash') || $dyhb_hash != get_cookie('hash')) {
	 set_cookie('hash',$dyhb_hash,'86400');
}

/** ��½״̬ */
$dyhb_premission=unserialize($dyhb_options['dyhb_global_prefconfig'.$dyhb_usergroup]);
define('ISLOGIN',$dyhb_userid?true:false);
define('LOGIN_USERGROUNP', ISLOGIN === true ? $_USERINFOR['dyhb_usergroup'] : '0');//�û���: 1����Ա, 2����׫д��, 3ע���û�,visitor�ÿ�
define('LOGIN_USERID', ISLOGIN === true ? $_USERINFOR['dyhb_userid'] : '');//�û�ID

/**
  * ǰ��̨���û������ݣ��û�ϵͳ����
  * ˵��:���Ƕ������飬����������λ�ÿ��Ե�������
  *
  */
$_sideBlogger=ReadCache('side_blogger');
$_sideSorts=ReadCache('side_sorts');
$_globalTreeSorts=ReadCache('global_threesorts');
$_sidePhotoSorts=ReadCache('side_photosorts');
$_sideMp3Sorts=ReadCache('side_mp3sorts');
$side_Links=ReadCache('side_links');
$side_LogoLinks=$side_Links['1'];
$side_TextLinks=$side_Links['0'];
$_sideRecord=ReadCache('side_record');
$Blog=ReadCache('side_blog');
$TheTag=ReadCache('the_tag');
$TagCloud=$TheTag[0];
$HotTag=$TheTag[1];

/**
  * ǰ��̨���ñ���
  * ˵��:�������ڳ�����$View��һ�����ݴ���$view�ǵڶ������ݴ���
  *
  */
$View=sql_filter( sql_check (CheckSql( get_args('action') )));
$view= sql_filter (sql_check(CheckSql( get_args('do') )));
$view2=sql_filter( sql_check(CheckSql( get_args('do2') )));
$page = intval( get_args('page') );

/** ����ҹ������ڴ�ŵ�ǰ���еĲ�� .*/
$DyhbHooks=array();

/** ����ȫ�ֲ�����ݣ���������� */
$PluginList=ReadCache('plugin_list');
if($PluginList){
      foreach($PluginList as $value){
         if(file_exists(DOYOUHAOBABY_ROOT."/width/plugins/{$value[dir]}/{$value[dir]}.php")){
	          include_once(DOYOUHAOBABY_ROOT."/width/plugins/{$value[dir]}/{$value[dir]}.php");
          }
      }
}

?>