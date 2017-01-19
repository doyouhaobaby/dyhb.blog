<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：width.php
        * 说明：全局/width/有容乃大
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

/** 程序错误报告级别，如果程序出现警告，请设置其为0 */
error_reporting(7);

/** 缓冲开始,加速 */
ob_start();

/** 程序默认编码字符编码，暂时只能是中文 */
header("content-Type: text/html; charset=GBK");

/** 页面计时器开始 */
$Microtime = explode(' ', microtime());
$Starttime =$Microtime [1]+ $Microtime[0];

/** 程序物理路径 */
require_once("root.php");

/**  程序全局配置文件 */
require_once(DOYOUHAOBABY_ROOT.'/includes/config.php');
require_once(DOYOUHAOBABY_ROOT.'/includes/dyhb.config.php');

/** 加载函数库,用于全局数据处理 */
foreach(array('gbk','html2ubb','img','ip','plugin','time','visitor','sql','log','cache.html','get.post','session','page','tpl','safe','base','notice','smarty','rewrite','model','digg','sendemail') as $val){
   $FuncPath=DOYOUHAOBABY_ROOT."/base.func/$val.func.php";
   if(file_exists($FuncPath)){
       require_once($FuncPath);
   }
}

/** session登陆状态记录 */
require_once(DOYOUHAOBABY_ROOT.'/includes/a.session.php');

/** mysql数据库装载 */
require_once(DOYOUHAOBABY_ROOT.'/class.lib/class.mysql.php');
$DB = new Mysql (DB_HOST,DB_USER,DB_PASSWORD);
$DB->selectdb (DB_NAME);
$DB->setchar(DB_UNICODE);

/** 重建缓存,清理缓存和系统初始化时启动，缓存全局配置 */
if (!@ file_exists(DOYOUHAOBABY_ROOT.'/width/cache/c_dyhb_options.php')&&!@ file_exists(DOYOUHAOBABY_ROOT.'/install.php')) {
	 CacheOptions();
	 DyhbMessage("全站配置缓存已经建立，你需要登录后台建立全站缓存！<br>The station configuration cache has been established, you need to admin control panel cache for the whole station!",'0');
}

/** 数据库操作封装类，程序数据中心，结果为$_Logs=new Logs($DB) */
foreach(array('cools','logs','sorts','comments','tags','links','photosorts','mp3s','trackbacks') as $val){
    $Val=ucwords($val);
    $ClassValue='_'.$Val;
    require_once(DOYOUHAOBABY_ROOT."/class.lib/class.{$val}.php");
    if(!class_exists($Val)){
	       DyhbMessage($common_width[1].$Val,'0');
    }
    $$ClassValue=new $Val($DB);
}

/** 读取程序全局配置缓存 */
$dyhb_options=ReadCache('dyhb_options');

/** 加载共用语言包 */
/* 添加默认语言包地址，防止未生成缓存不能访问站点！ */
$dyhb_options[global_lang_select]=$dyhb_options[global_lang_select]?$dyhb_options[global_lang_select]:"zh_cn";
require_once(DOYOUHAOBABY_ROOT."/images/lang/$dyhb_options[global_lang_select]/notice.php");
require_once(DOYOUHAOBABY_ROOT."/images/lang/$dyhb_options[global_lang_select]/width.php");

/** 使用者所在的时区，发布日志，评论等等需要用到 */
$timezone  = intval($dyhb_options['timezone']);
$localdate = time() - ($timezone - 8) * 3600;

/** 登录与用户,登陆用户级别 */

/** 注销，登陆用户推出 */
if(isset($_GET['login_out'])&&$_GET['login_out']=="true"){
    LoginOut();
}

/** 在线Ip,auth */
$dyhb_onlineip =  getIp();
$dyhb_auth_key = md5($dyhb_onlineip.$_SERVER['HTTP_USER_AGENT']);

/** 获取密码，用户信息 */
list($dyhb_userid, $dyhb_password, $dyhb_logincount) = get_cookie('auth') ? explode("\t", authcode(get_cookie('auth'), 'DECODE')) : array('', '', 0);
$dyhb_hash = dyhb_addslashes(get_cookie('hash'));
$dyhb_userid = intval($dyhb_userid);
$dyhb_password = dyhb_addslashes($dyhb_password);
$dyhb_logincount = intval($dyhb_logincount);

/** 1，管理员，2，联合撰写，3，注册用户，4，超级管理员，5游客 */
$dyhb_usergroup = 5;

/** 用户信息容器 */
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

/** 登陆状态 */
$dyhb_premission=unserialize($dyhb_options['dyhb_global_prefconfig'.$dyhb_usergroup]);
define('ISLOGIN',$dyhb_userid?true:false);
define('LOGIN_USERGROUNP', ISLOGIN === true ? $_USERINFOR['dyhb_usergroup'] : '0');//用户组: 1管理员, 2联合撰写人, 3注册用户,visitor访客
define('LOGIN_USERID', ISLOGIN === true ? $_USERINFOR['dyhb_userid'] : '');//用户ID

/**
  * 前后台公用缓存数据，用户系统提速
  * 说明:她们都是数组，程序中任意位置可以调用它们
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
  * 前后台公用变量
  * 说明:她们用于程序处理，$View是一层数据处理，$view是第二层数据处理
  *
  */
$View=sql_filter( sql_check (CheckSql( get_args('action') )));
$view= sql_filter (sql_check(CheckSql( get_args('do') )));
$view2=sql_filter( sql_check(CheckSql( get_args('do2') )));
$page = intval( get_args('page') );

/** 插件挂钩，用于存放当前运行的插件 .*/
$DyhbHooks=array();

/** 加载全局插件数据，供程序调用 */
$PluginList=ReadCache('plugin_list');
if($PluginList){
      foreach($PluginList as $value){
         if(file_exists(DOYOUHAOBABY_ROOT."/width/plugins/{$value[dir]}/{$value[dir]}.php")){
	          include_once(DOYOUHAOBABY_ROOT."/width/plugins/{$value[dir]}/{$value[dir]}.php");
          }
      }
}

?>