<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：install.php
        * 说明：安装程序
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

define('DOYOUHAOBABY_ROOT',dirname(__FILE__));

//加载必要文件
require_once('base.func/base.func.php');
require_once('class.lib/class.mysql.php');
$view=isset($_GET['do'])?$_GET['do']:'';

$c_files="includes/config.php";
if(!is_writable($c_files)){
  DyhbMessage("配置文件config.php不可写，安装程序无法进行,请设置权限为777.",'javascript:history.back(-1);');
}
if(isset($_POST['install'])||$view=="mustinstall"){   
 $localhost=trim($_POST['localhost']);
 $username=trim($_POST['username']);
 $dbname=trim($_POST['dbname']);
 $password=trim($_POST['password']);
 $prefix=trim($_POST['prefix']);
 $admin_username=trim($_POST['admin_username']);
 $admin_password1=trim($_POST['admin_password']);
 $admin_password=md5(trim($_POST['admin_password']));
 //对用户填写数据进行检查
 if(!$localhost){
    DyhbMessage("MySql主机名不能为空，一般为localhost.",'javascript:history.back(-1);');
 }
 if(!$username){
    DyhbMessage("MySql用户名不能为空.",'javascript:history.back(-1);');
 }
 if(!$dbname){
    DyhbMessage("MySql数据库名不能为空.",'javascript:history.back(-1);');
 }
 if(!$prefix){
    DyhbMessage("MySql数据库表前缀名不能为空.",'javascript:history.back(-1);');
 }
 if(!$admin_username){
    DyhbMessage("后台管理用户名不能为空.",'javascript:history.back(-1);');
 }
 if(!$admin_password){
    DyhbMessage("后台管理密码不能为空.",'javascript:history.back(-1);');
 }
 $dyhb_c='<?php ';
 $dyhb_c.="\n\n# mysql数据库地址，默认为localhost\n";
 $dyhb_c.="define('DB_HOST','$localhost');";
 $dyhb_c.="\n# mysql数据库用户名\n";
 $dyhb_c.="define('DB_USER','$username');";
 $dyhb_c.="\n# mysql数据用户密码\n";
 $dyhb_c.="define('DB_PASSWORD','$password');";
 $dyhb_c.="\n# mysql数据库名\n";
 $dyhb_c.="define('DB_NAME','$dbname');";
 $dyhb_c.="\n# mysql数据库前缀\n";
 $dyhb_c.="define('DB_PREFIX','$prefix');";
 $dyhb_c.="\n# mysql数据库连接编码，默认为gbk\n";
 $dyhb_c.="define('DB_UNICODE','gbk');";
 $dyhb_c.="\n\n";
 $dyhb_c.='?>';
 //将文件写入配置文件
 $fp=fopen($c_files,"w+");
 @ fwrite($fp,$dyhb_c);
 require_once('includes/config.php');

//mysql
$DB = new Mysql (DB_HOST,DB_USER,DB_PASSWORD);
$DB->selectdb (DB_NAME);
$DB->setchar(DB_UNICODE);
 
//检查数据库中是否已经存在相同的数据
if($view!="mustinstall"&&$DB->getresultnum("SHOW TABLES LIKE '{$prefix}blog'")){
  echo<<<DYHB
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>DoYouHaoBaby博客--安装程序--是否覆盖已有数据？</title>
<meta http-equiv="content-type" content="application/xhtml+xml; charset=gbk" />
<link rel="stylesheet" href="images/common.css" type="text/css" />
</head>
<body>
 <form method="post" action="install.php?do=mustinstall">
 <div id="main">
  <input type='hidden' name='localhost' value="{$localhost}">
  <input type='hidden' name='username' value="{$username}">
  <input type='hidden' name='password' value="{$password}">
  <input type='hidden' name='dbname' value="{$dbname}">
  <input type='hidden' name='prefix' value="{$prefix}">
  <input type='hidden' name='admin_username' value="{$admin_username}">
  <input type='hidden' name='admin_password' value="{$admin_password1}">
  <h1>可能带来危险性的操作</h1>
  <p>本次操作不可恢复，请确认你是否做好了准备？</p>
  数据库中已经存在相同的数据库表了。继续安装可能会覆盖掉原有的数据，你要继续吗？<br>
  （<font color="red">如果不想覆盖安装，请重新填写数据库表前缀名，<a href="install.php">点击重新填写数据库前缀</a></font>）<br>
  <input name="Submit" type="submit" value="安装&raquo;">
  </div>
  </form>
</body>
</html>
DYHB;
  exit();
}

//博客固定地址
$blog_url=getUrl();

//sql数据库结构
$sql_query[]="DROP TABLE IF EXISTS {$prefix}blog;";
$sql_query[]="
CREATE TABLE `{$prefix}blog` (
  `blog_id` mediumint(8) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `dateline` int(10) NOT NULL,
  `content` longtext NOT NULL,
  `from` varchar(25) NOT NULL,
  `fromurl` varchar(255) NOT NULL,
  `urlname` varchar(255) NOT NULL,
  `user_id` mediumint(8) NOT NULL default '-1',
  `sort_id` mediumint(8) NOT NULL default '-1',
  `thumb` varchar(255) NOT NULL,
  `viewnum` mediumint(8) unsigned NOT NULL default '1',
  `password` tinytext NOT NULL,
  `istop` tinyint(1) NOT NULL default '0',
  `isshow` tinyint(1) NOT NULL default '1',
  `islock` tinyint(1) NOT NULL default '0',
  `ispage` tinyint(1) NOT NULL default '0',
  `trackbacknum` mediumint(8) NOT NULL default '0',
  `istrackback` tinyint(1) NOT NULL default '1',
  `commentnum` mediumint(8) NOT NULL default '0',
  `good` int(8) NOT NULL default '0',
  `bad` int(8) NOT NULL default '0',
  `ismobile` tinyint(1) NOT NULL default '0',
  `keyword` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY  (`blog_id`),
  KEY `user_id` (`user_id`),
  KEY `istop` (`istop`,`isshow`,`islock`,`ispage`),
  KEY `dateline` (`dateline`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk;
";

$sql_query[]="
INSERT INTO `{$prefix}blog` (`blog_id`, `title`, `dateline`, `content`, `from`, `fromurl`, `urlname`, `user_id`, `sort_id`, `thumb`, `viewnum`, `password`, `istop`, `isshow`, `islock`, `ispage`, `trackbacknum`, `istrackback`, `commentnum`, `good`, `bad`, `ismobile`, `keyword`, `description`) VALUES
(1, 'hello world !', 1283882993, '欢迎使用DYHB-BLOG，虽然是个博客程序，但是完全可以打造成一个CMS &nbsp;or BBS', '本站原创', '', '', 1, -1, '', 2, '', 0, 1, 0, 0, 0, 1, 0, 0, 0, 0, '', '');
";				

$sql_query[]="DROP TABLE IF EXISTS {$prefix}comment;";
$sql_query[]="
CREATE TABLE `{$prefix}comment` (
  `comment_id` mediumint(8) unsigned NOT NULL auto_increment,
  `blog_id` mediumint(8) unsigned NOT NULL default '0',
  `taotao_id` mediumint(8) unsigned NOT NULL default '0',
  `mp3_id` mediumint(8) unsigned NOT NULL default '0',
  `file_id` mediumint(8) unsigned NOT NULL default '0',
  `dateline` int(10) NOT NULL,
  `name` varchar(25) NOT NULL,
  `comment` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `isshow` tinyint(1) NOT NULL default '1',
  `ip` varchar(16) NOT NULL,
  `parentcomment_id` mediumint(8) NOT NULL default '0',
  `isreplymail` tinyint(1) NOT NULL default '0',
  `ismobile` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`comment_id`),
  KEY `blog_id` (`blog_id`,`taotao_id`,`mp3_id`,`file_id`),
  KEY `isshow` (`isshow`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk;
";

$sql_query[]="DROP TABLE IF EXISTS {$prefix}file;";
$sql_query[]="
CREATE TABLE `{$prefix}file` (
  `file_id` mediumint(8) NOT NULL auto_increment,
  `photosort_id` mediumint(8) NOT NULL default '-1',
  `path` varchar(255) default NULL,
  `filetype` varchar(50) NOT NULL,
  `download` mediumint(8) NOT NULL default '0',
  `name` varchar(255) default NULL,
  `dateline` int(10) default NULL,
  `size` bigint(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY  (`file_id`),
  KEY `photosort_id` (`photosort_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk;
";

$sql_query[]="DROP TABLE IF EXISTS {$prefix}link;";
$sql_query[]="
CREATE TABLE `{$prefix}link` (
  `link_id` mediumint(8) unsigned NOT NULL auto_increment,
  `name` varchar(30) NOT NULL,
  `url` varchar(75) NOT NULL,
  `description` varchar(255) NOT NULL,
  `logo` varchar(360) NOT NULL default '0',
  `isdisplay` tinyint(1) NOT NULL default '1',
  `compositor` smallint(8) NOT NULL,
  PRIMARY KEY  (`link_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk;
";

$sql_query[]="INSERT INTO `{$prefix}link` (`link_id`, `name`, `url`, `description`, `logo`, `isdisplay`, `compositor`) VALUES
(1, '官方论坛', 'http://bbs.56swun.com', '', '', 1, 0),
(2, 'DYHB-blog主页', 'http://www.doyouhaobaby.com', '', '', 1, 0),
(3, 'A5下载', 'http://down.admin5.com', '', '', 1, 0),
(4, '华夏名网', 'http://sudu.cn', '', '', 1, 0),
(5, 'Wopus', 'http://www.wopus.org', '', '', 1, 0);
";

$sql_query[]="DROP TABLE IF EXISTS {$prefix}mp3;";
$sql_query[]="
CREATE TABLE `{$prefix}mp3` (
  `mp3_id` mediumint(8) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `dateline` int(10) NOT NULL,
  `mp3sort_id` mediumint(8) NOT NULL default '-1',
  `musicword` text NOT NULL,
  PRIMARY KEY  (`mp3_id`),
  KEY `mp3sort_id` (`mp3sort_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk;
";

$sql_query[]="DROP TABLE IF EXISTS {$prefix}mp3sort;";
$sql_query[]="
CREATE TABLE `{$prefix}mp3sort` (
  `mp3sort_id` mediumint(8) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL,
  `isdisplay` tinyint(1) NOT NULL default '0',
  `compositor` smallint(8) NOT NULL default '0',
  PRIMARY KEY  (`mp3sort_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk;
";

$sql_query[]="DROP TABLE IF EXISTS {$prefix}option;";
$sql_query[]="
CREATE TABLE `{$prefix}option` (
  `value` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `option_id` smallint(8) unsigned NOT NULL,
  PRIMARY KEY  (`option_id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
";

$sql_query[]=<<<DYHB
INSERT INTO `{$prefix}option` (`value`, `name`, `option_id`) VALUES
('20', 'admin_log_num', 7),
('蜀TMD备案', 'icp', 5),
('default', 'user_template', 6),
('Not TM，But TMD !', 'blog_information', 3),
('5', 'user_log_num', 4),
('DYHB-blog', 'blog_title', 2),
('10', 'hot_log_num', 8),
('5', 'rand_log_num', 9),
('10', 'relate_log_num', 10),
('5', 'new_log_num', 11),
('5', 'new_comment_num', 12),
('1,8', 'cms_new_log_num', 14),
('5', 'log_comment_num', 17),
('a:24:{s:6:"search";s:1:"0";s:7:"blogger";s:1:"1";s:5:"login";s:1:"2";s:8:"calendar";s:1:"3";s:8:"microlog";s:1:"4";s:4:"sort";s:1:"5";s:6:"record";s:1:"6";s:7:"comment";s:1:"7";s:9:"guestbook";s:1:"8";s:6:"hottag";s:1:"9";s:6:"newlog";s:2:"10";s:7:"randlog";s:2:"11";s:6:"hotlog";s:2:"12";s:10:"commentlog";s:2:"13";s:7:"yearlog";s:2:"14";s:8:"mouthlog";s:2:"15";s:7:"mp3sort";s:2:"16";s:5:"music";s:2:"17";s:3:"mp3";s:2:"18";s:9:"photosort";s:2:"19";s:8:"newphoto";s:2:"20";s:3:"tpl";s:2:"21";s:4:"link";s:2:"22";s:7:"theblog";s:2:"23";}', 'sidebar_widget_sort2', 18),
('15', 'rss_log_num', 21),
('50', 'user_comment_str_num', 23),
('100', 'admin_comment_str_num', 24),
('http://www.doyouhaobaby.com', 'blog_program_url', 32),
('免费个人博客，西南民大，单用户博客程序，小牛哥，DYHB，新一代cms博客', 'blog_description', 25),
('DYHB-BLOG', 'prower_blog_name', 30),
('致力于个人信息发布，允许高级的cms扩展！', 'blog_keyword', 26),
('<meta name="author" content="www.doyouhaobaby.com" />', 'blog_othorhead', 27),
('$blog_url', 'blogurl', 28),
('8', 'timezone', 29),
('1', 'all_count_num', 39),
('15', 'comment_log_num', 34),
('5', 'year_hot_log_num', 35),
('5', 'mouth_hot_log_num', 37),
('1', 'today_count_num', 40),
('20100907', 'daytime', 41),
('0', 'gzipcompress', 42),
('5', 'new_guestbook_num', 43),
('33', 'taotao_show_num', 44),
('33', 'taotao_cut_num', 45),
('40', 'cms_content_num', 46),
('default', 'admin_template', 47),
('a:4:{i:0;s:6:"00bb11";i:1;s:6:"dd7722";i:2;s:6:"dd55ff";i:3;s:6:"00aadd";}', 'side_tag_color', 49),
('233333233', 'uploadfile_maxsize', 50),
('a:9:{i:0;s:3:"rar";i:1;s:3:"zip";i:2;s:3:"gif";i:3;s:3:"jpg";i:4;s:4:"jpeg";i:5;s:3:"png";i:6;s:3:"bmp";i:7;s:3:"php";i:8;s:3:"mp3";}', 'all_allowed_filetype', 51),
('a:4:{i:0;s:3:"gif";i:1;s:3:"png";i:2;s:3:"jpg";i:3;s:4:"jpeg";}', 'qq_allowed_filetype', 52),
('a:2:{i:0;s:3:"140";i:1;s:3:"220";}', 'icon_width_height', 53),
('a:2:{i:0;s:4:"3000";i:1;s:5:"30000";}', 'all_width_height', 54),
('1', 'file_store_bydate', 55),
('12', 'every_file_num', 56),
('a:3:{i:0;s:3:"yes";i:1;s:3:"yes";i:2;s:3:"yes";}', 'mp3player_options', 57),
('22', 'every_guest_num', 58),
('15', 'every_photo_num', 59),
('a:24:{s:6:"search";s:4:"搜索";s:7:"blogger";s:7:"blogger";s:5:"login";s:8:"管理操作";s:8:"calendar";s:4:"日历";s:8:"microlog";s:8:"Microlog";s:4:"sort";s:8:"日志分类";s:6:"record";s:8:"日志归档";s:7:"comment";s:8:"最新评论";s:9:"guestbook";s:8:"最新留言";s:6:"hottag";s:8:"热门标签";s:6:"newlog";s:8:"最新日志";s:7:"randlog";s:8:"随机日志";s:6:"hotlog";s:8:"访问排行";s:10:"commentlog";s:8:"评论排行";s:7:"yearlog";s:8:"年度排行";s:8:"mouthlog";s:8:"当月排行";s:7:"mp3sort";s:8:"音乐分类";s:5:"music";s:8:"最新音乐";s:3:"mp3";s:4:"音乐";s:9:"photosort";s:8:"相册分类";s:8:"newphoto";s:8:"最新照片";s:3:"tpl";s:8:"更换模板";s:4:"link";s:4:"衔接";s:7:"theblog";s:8:"全站信息";}', 'sidebar_widget_name', 60),
('a:24:{s:6:"search";s:1:"0";s:7:"blogger";s:1:"1";s:5:"login";s:1:"2";s:8:"calendar";s:1:"3";s:8:"microlog";s:1:"4";s:4:"sort";s:1:"5";s:6:"record";s:1:"6";s:7:"comment";s:1:"7";s:9:"guestbook";s:1:"8";s:6:"hottag";s:1:"9";s:6:"newlog";s:2:"10";s:7:"randlog";s:2:"11";s:6:"hotlog";s:2:"12";s:10:"commentlog";s:2:"13";s:7:"yearlog";s:2:"14";s:8:"mouthlog";s:2:"15";s:7:"mp3sort";s:2:"16";s:5:"music";s:2:"17";s:3:"mp3";s:2:"18";s:9:"photosort";s:2:"19";s:8:"newphoto";s:2:"20";s:3:"tpl";s:2:"21";s:4:"link";s:2:"22";s:7:"theblog";s:2:"23";}', 'sidebar_widget_sort', 61),
('4', 'every_wap_file_num', 145),
('a:24:{s:6:"search";s:1:"1";s:7:"blogger";s:1:"1";s:5:"login";s:1:"1";s:8:"calendar";s:1:"0";s:8:"microlog";s:1:"0";s:4:"sort";s:1:"1";s:6:"record";s:1:"1";s:7:"comment";s:1:"0";s:9:"guestbook";s:1:"0";s:6:"hottag";s:1:"0";s:6:"newlog";s:1:"1";s:7:"randlog";s:1:"0";s:6:"hotlog";s:1:"0";s:10:"commentlog";s:1:"0";s:7:"yearlog";s:1:"0";s:8:"mouthlog";s:1:"0";s:7:"mp3sort";s:1:"0";s:5:"music";s:1:"0";s:3:"mp3";s:1:"0";s:9:"photosort";s:1:"0";s:8:"newphoto";s:1:"0";s:3:"tpl";s:1:"0";s:4:"link";s:1:"1";s:7:"theblog";s:1:"1";}', 'sidebar_widget_show', 62),
('a:2:{i:0;s:3:"100";i:1;s:3:"100";}', 'wapfilelist_width_height', 146),
('0', 'user_code', 64),
('1', 'admin_code', 65),
('20', 'every_taotao_num', 63),
('a:24:{s:6:"search";s:1:"1";s:7:"blogger";s:1:"1";s:5:"login";s:1:"1";s:8:"calendar";s:1:"0";s:8:"microlog";s:1:"0";s:4:"sort";s:1:"1";s:6:"record";s:1:"1";s:7:"comment";s:1:"0";s:9:"guestbook";s:1:"0";s:6:"hottag";s:1:"0";s:6:"newlog";s:1:"1";s:7:"randlog";s:1:"0";s:6:"hotlog";s:1:"0";s:10:"commentlog";s:1:"0";s:7:"yearlog";s:1:"0";s:8:"mouthlog";s:1:"0";s:7:"mp3sort";s:1:"0";s:5:"music";s:1:"0";s:3:"mp3";s:1:"0";s:9:"photosort";s:1:"0";s:8:"newphoto";s:1:"0";s:3:"tpl";s:1:"0";s:4:"link";s:1:"1";s:7:"theblog";s:1:"1";}', 'sidebar_widget_show2', 66),
('a:24:{s:6:"search";s:1:"1";s:7:"blogger";s:1:"1";s:5:"login";s:1:"1";s:8:"calendar";s:1:"0";s:8:"microlog";s:1:"0";s:4:"sort";s:1:"1";s:6:"record";s:1:"1";s:7:"comment";s:1:"0";s:9:"guestbook";s:1:"0";s:6:"hottag";s:1:"0";s:6:"newlog";s:1:"1";s:7:"randlog";s:1:"0";s:6:"hotlog";s:1:"0";s:10:"commentlog";s:1:"0";s:7:"yearlog";s:1:"0";s:8:"mouthlog";s:1:"0";s:7:"mp3sort";s:1:"0";s:5:"music";s:1:"0";s:3:"mp3";s:1:"0";s:9:"photosort";s:1:"0";s:8:"newphoto";s:1:"0";s:3:"tpl";s:1:"0";s:4:"link";s:1:"1";s:7:"theblog";s:1:"1";}', 'sidebar_widget_show3', 67),
('a:24:{s:6:"search";s:1:"1";s:7:"blogger";s:1:"1";s:5:"login";s:1:"1";s:8:"calendar";s:1:"0";s:8:"microlog";s:1:"0";s:4:"sort";s:1:"1";s:6:"record";s:1:"1";s:7:"comment";s:1:"0";s:9:"guestbook";s:1:"0";s:6:"hottag";s:1:"0";s:6:"newlog";s:1:"1";s:7:"randlog";s:1:"0";s:6:"hotlog";s:1:"0";s:10:"commentlog";s:1:"0";s:7:"yearlog";s:1:"0";s:8:"mouthlog";s:1:"0";s:7:"mp3sort";s:1:"0";s:5:"music";s:1:"0";s:3:"mp3";s:1:"0";s:9:"photosort";s:1:"0";s:8:"newphoto";s:1:"0";s:3:"tpl";s:1:"0";s:4:"link";s:1:"1";s:7:"theblog";s:1:"1";}', 'sidebar_widget_show4', 68),
('a:24:{s:6:"search";s:1:"0";s:7:"blogger";s:1:"1";s:5:"login";s:1:"2";s:8:"calendar";s:1:"3";s:8:"microlog";s:1:"4";s:4:"sort";s:1:"5";s:6:"record";s:1:"6";s:7:"comment";s:1:"7";s:9:"guestbook";s:1:"8";s:6:"hottag";s:1:"9";s:6:"newlog";s:2:"10";s:7:"randlog";s:2:"11";s:6:"hotlog";s:2:"12";s:10:"commentlog";s:2:"13";s:7:"yearlog";s:2:"14";s:8:"mouthlog";s:2:"15";s:7:"mp3sort";s:2:"16";s:5:"music";s:2:"17";s:3:"mp3";s:2:"18";s:9:"photosort";s:2:"19";s:8:"newphoto";s:2:"20";s:3:"tpl";s:2:"21";s:4:"link";s:2:"22";s:7:"theblog";s:2:"23";}', 'sidebar_widget_sort3', 69),
('a:24:{s:6:"search";s:1:"0";s:7:"blogger";s:1:"1";s:5:"login";s:1:"2";s:8:"calendar";s:1:"3";s:8:"microlog";s:1:"4";s:4:"sort";s:1:"5";s:6:"record";s:1:"6";s:7:"comment";s:1:"7";s:9:"guestbook";s:1:"8";s:6:"hottag";s:1:"9";s:6:"newlog";s:2:"10";s:7:"randlog";s:2:"11";s:6:"hotlog";s:2:"12";s:10:"commentlog";s:2:"13";s:7:"yearlog";s:2:"14";s:8:"mouthlog";s:2:"15";s:7:"mp3sort";s:2:"16";s:5:"music";s:2:"17";s:3:"mp3";s:2:"18";s:9:"photosort";s:2:"19";s:8:"newphoto";s:2:"20";s:3:"tpl";s:2:"21";s:4:"link";s:2:"22";s:7:"theblog";s:2:"23";}', 'sidebar_widget_sort4', 70),
('1', 'blog_is_open', 71),
('1', 'wap_is_open', 72),
('网站升级中...', 'why_blog_close', 73),
('2', 'mobile_log_num', 74),
('15', 'mobile_tag_num', 75),
('5', 'mobile_comment_num', 76),
('10', 'mobile_taotao_num', 77),
('1', 'is_auto_cut', 78),
('200', 'auto_cut_num', 79),
('22', 'admin_tag_num', 81),
('1', 'enable_trackback', 82),
('<a href="#">流量统计</a>', 'visitor_count_html', 97),
('0', 'com_examine', 84),
('a:14:{i:0;s:6:"ff80ff";i:1;s:6:"357DCE";i:2;s:6:"F2F2F2";i:3;s:6:"000000";i:4;s:6:"8080ff";i:5;s:6:"F2F2F2";i:6;s:6:"FFFFFF";i:7;s:6:"800080";i:8;s:6:"c0c0c0";i:9;s:6:"FFFFFF";i:10;s:6:"FFFFFF";i:11;s:6:"8EC2F4";i:12;s:3:"yes";i:13;s:2:"no";}', 'setplayer', 94),
('a:0:{}', 'plugin_navbar', 87),
('N;', 'plugin_self_help', 88),
('0', 'register_code', 89),
('1', 'cache_cms_log', 90),
('1', 'allowed_send', 91),
('0', 'allowed_shenghe', 92),
('0', 'is_allow_tolocalimg', 95),
('5', 'new_music_num', 98),
('4', 'new_photo_num', 99),
('22', 'hot_tag_num', 100),
('0', 'allowed_make_html', 48),
('a:8:{s:3:"tag";s:4:"标签";s:4:"link";s:4:"链接";s:6:"search";s:4:"搜索";s:6:"record";s:4:"归档";s:3:"mp3";s:4:"音乐";s:5:"photo";s:4:"相册";s:8:"microlog";s:4:"微博";s:9:"guestbook";s:6:"留言板";}', 'modellist', 31),
('', 'ad_footer', 16),
('', 'ad_header', 15),
('', 'ad_sidebar', 19),
('', 'ad_showlog', 20),
('0', 'is_autosave', 1),
('', 'ad_logo_beside', 13),
('1', 'is_image_leech', 22),
('0', 'is_image_inline', 33),
('a:1:{i:0;s:0:"";}', 'is_leech_domail', 80),
('', 'tag_base', 83),
('', 'sort_base', 85),
('default', 'permalink_structure', 101),
('4', 'thread_comments_depth', 36),
('a:8:{s:3:"tag";i:1;s:4:"link";i:1;s:6:"search";i:1;s:6:"record";i:1;s:3:"mp3";i:1;s:5:"photo";i:1;s:8:"microlog";i:1;s:9:"guestbook";i:1;}', 'modellist_show', 93),
('a:8:{s:3:"tag";i:1;s:4:"link";i:2;s:6:"search";i:3;s:6:"record";i:4;s:3:"mp3";i:5;s:5:"photo";i:6;s:8:"microlog";i:7;s:9:"guestbook";i:8;}', 'modellist_sort', 96),
('scroll', 'blog_background_attachment', 114),
('Y-m-d h:i:s', 'commentdateformat', 102),
('1', 'commentsrrlnofollow', 103),
('1', 'show_avatars', 104),
('G', 'avatar_rating', 105),
('identicon', 'avatar_default', 106),
('32', 'comment_avatars_size', 107),
('a:26:{s:5:"visit";s:1:"0";s:14:"seehiddenentry";s:1:"0";s:16:"seehiddencomment";s:1:"0";s:5:"seeip";s:1:"0";s:12:"viewuserlist";s:1:"1";s:13:"viewphotolist";s:1:"1";s:14:"viewuserdetail";s:1:"1";s:20:"seeallprotectedentry";s:1:"1";s:8:"addentry";s:1:"1";s:9:"editentry";s:1:"1";s:14:"editotherentry";s:1:"1";s:6:"addtag";s:1:"1";s:12:"leavemessage";s:1:"1";s:9:"sendentry";s:1:"1";s:12:"sendmicrolog";s:1:"1";s:15:"minpostinterval";s:1:"1";s:6:"nospam";s:1:"1";s:4:"html";s:1:"1";s:13:"editotheruser";s:1:"1";s:8:"topentry";s:1:"1";s:2:"cp";s:1:"1";s:11:"allowsearch";s:1:"1";s:14:"fulltextsearch";s:1:"1";s:6:"upload";s:1:"1";s:8:"downfile";s:1:"1";s:6:"gpname";s:6:"管理员";}', 'dyhb_global_prefconfig1', 126),
('', 'blog_background_img_wide', 112),
('http://bbs.56swun.com/attachments/forumid_20/1008261024fdcfeba070ad0d89.gif', 'blog_logo', 113),
('images/template/header_default_2.jpg', 'default_header', 108),
('#F9FAFC', 'blog_background_color', 110),
('', 'blog_background_img', 111),
('', 'blog_top_img_or_word', 120),
('', 'blog_sidebar_word_color', 122),
('', 'blog_sidebar_word_size', 123),
('a:26:{s:5:"visit";s:1:"1";s:14:"seehiddenentry";s:1:"0";s:16:"seehiddencomment";s:1:"0";s:5:"seeip";s:1:"0";s:12:"viewuserlist";s:1:"1";s:13:"viewphotolist";s:1:"1";s:14:"viewuserdetail";s:1:"1";s:20:"seeallprotectedentry";s:1:"0";s:8:"addentry";s:1:"1";s:9:"editentry";s:1:"0";s:14:"editotherentry";s:1:"0";s:6:"addtag";s:1:"0";s:12:"leavemessage";s:1:"1";s:9:"sendentry";s:1:"1";s:12:"sendmicrolog";s:1:"1";s:15:"minpostinterval";s:1:"1";s:6:"nospam";s:1:"0";s:4:"html";s:1:"0";s:13:"editotheruser";s:1:"0";s:8:"topentry";s:1:"0";s:2:"cp";s:1:"0";s:11:"allowsearch";s:1:"1";s:14:"fulltextsearch";s:1:"1";s:6:"upload";s:1:"1";s:8:"downfile";s:1:"1";s:6:"gpname";s:8:"联合撰写";}', 'dyhb_global_prefconfig2', 127),
('a:26:{s:5:"visit";s:1:"1";s:14:"seehiddenentry";s:1:"0";s:16:"seehiddencomment";s:1:"0";s:5:"seeip";s:1:"0";s:12:"viewuserlist";s:1:"0";s:13:"viewphotolist";s:1:"0";s:14:"viewuserdetail";s:1:"1";s:20:"seeallprotectedentry";s:1:"0";s:8:"addentry";s:1:"1";s:9:"editentry";s:1:"0";s:14:"editotherentry";s:1:"0";s:6:"addtag";s:1:"0";s:12:"leavemessage";s:1:"1";s:9:"sendentry";s:1:"1";s:12:"sendmicrolog";s:1:"0";s:15:"minpostinterval";s:1:"0";s:6:"nospam";s:1:"0";s:4:"html";s:1:"0";s:13:"editotheruser";s:1:"0";s:8:"topentry";s:1:"0";s:2:"cp";s:1:"0";s:11:"allowsearch";s:1:"1";s:14:"fulltextsearch";s:1:"0";s:6:"upload";s:1:"0";s:8:"downfile";s:1:"1";s:6:"gpname";s:8:"注册用户";}', 'dyhb_global_prefconfig3', 128),
('a:26:{s:5:"visit";s:1:"1";s:14:"seehiddenentry";s:1:"0";s:16:"seehiddencomment";s:1:"0";s:5:"seeip";s:1:"0";s:12:"viewuserlist";s:1:"0";s:13:"viewphotolist";s:1:"0";s:14:"viewuserdetail";s:1:"0";s:20:"seeallprotectedentry";s:1:"0";s:8:"addentry";s:1:"0";s:9:"editentry";s:1:"0";s:14:"editotherentry";s:1:"0";s:6:"addtag";s:1:"0";s:12:"leavemessage";s:1:"1";s:9:"sendentry";s:1:"1";s:12:"sendmicrolog";s:1:"0";s:15:"minpostinterval";s:1:"0";s:6:"nospam";s:1:"0";s:4:"html";s:1:"0";s:13:"editotheruser";s:1:"0";s:8:"topentry";s:1:"0";s:2:"cp";s:1:"0";s:11:"allowsearch";s:1:"1";s:14:"fulltextsearch";s:1:"0";s:6:"upload";s:1:"0";s:8:"downfile";s:1:"1";s:6:"gpname";s:8:"普通游客";}', 'dyhb_global_prefconfig5', 129),
('1', 'allowed_register', 130),
('1', 'side_sort_tree', 132),
('0', 'default_view_list', 133),
('1', 'see_the_sortvalue', 134),
('1', 'permalink_style', 135),
('0', 'sendmail_telladmin', 136),
('0', 'sendmail_isreplymail', 137),
('a:4:{s:4:"smtp";s:12:"smtp.126.com";s:4:"port";s:2:"25";s:9:"sendemail";s:15:"log1990@126.com";s:8:"password";s:0:"";}', 'sendemail', 138),
('1', 'is_oneplay_default', 144),
('1', 'upload_file_default', 139),
('15', 'file_input_num', 140),
('6', 'flash_log_num', 141),
('1', 'show_flash_log', 142),
('1', 'photo_isshow_hide', 143),
('a:2:{i:0;s:2:"50";i:1;s:2:"50";}', 'wapfilesortlist_width_height', 147),
('a:2:{i:0;s:3:"200";i:1;s:3:"200";}', 'wapfileshow_width_height', 148),
('0', 'url_chinese_english', 164),
('a:2:{i:0;s:4:"1000";i:1;s:4:"1000";}', 'blog_width_height', 149),
('1', 'is_makeimage_thumb', 150),
('a:2:{i:0;s:2:"50";i:1;s:2:"50";}', 'blogfile_thumb_width_heigth', 151),
('1', 'is_images_water_mark', 152),
('0', 'images_water_type', 153),
('7', 'images_water_position', 154),
('', 'images_water_mark_img_imgurl', 155),
('', 'images_water_mark_text_content', 156),
('', 'images_water_mark_text_color', 157),
('', 'images_water_mark_text_fontsize', 158),
('a:2:{i:0;s:2:"50";i:1;s:2:"50";}', 'blogicon_thumb_width_heigth', 159),
('1', 'thumb_is_water_mark', 160),
('0', 'is_float_div_action', 161),
('1', 'blog_cms_bbs', 162),
('1', 'after_login_back', 163),
('a:5:{i:0;s:3:"bbs";i:1;s:5:"sigua";i:2;s:4:"blog";i:3;s:8:"category";i:4;s:11:"record.html";}', 'protect_file_dir', 165),
('0', 'is_outlink_check', 167),
('zh_cn', 'global_lang_select', 166),
('0', 'is_jquery_effects', 168);
DYHB
;

$sql_query[]="DROP TABLE IF EXISTS {$prefix}session;";
$sql_query[]="
CREATE TABLE `{$prefix}session` (
  `hash` varchar(6) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `user_id` mediumint(8) NOT NULL,
  `usergroup` tinyint(3) NOT NULL,
  `seccode` varchar(6) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
";

$sql_query[]="DROP TABLE IF EXISTS {$prefix}photosort;";
$sql_query[]="
CREATE TABLE `{$prefix}photosort` (
  `photosort_id` mediumint(8) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `cover` varchar(255) NOT NULL,
  `compositor` smallint(8) NOT NULL default '0',
  PRIMARY KEY  (`photosort_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk;
";

$sql_query[]="DROP TABLE IF EXISTS {$prefix}sort;";
$sql_query[]="
CREATE TABLE `{$prefix}sort` (
  `sort_id` mediumint(8) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `urlname` varchar(255) NOT NULL,
  `cmsstart` tinyint(3) NOT NULL,
  `cmsend` tinyint(3) NOT NULL,
  `style` tinyint(1) NOT NULL default '1',
  `compositor` smallint(8) NOT NULL default '0',
  `logo` varchar(300) NOT NULL,
  `parentsort_id` mediumint(8) NOT NULL default '-1',
  `now` int(1) NOT NULL default '1',
  `keyword` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `introduce` varchar(255) NOT NULL,
  PRIMARY KEY  (`sort_id`),
  KEY `parentsort_id` (`parentsort_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk;
";


$sql_query[]="DROP TABLE IF EXISTS {$prefix}plugin;";
$sql_query[]="
CREATE TABLE `{$prefix}plugin` (
  `plugin_id` mediumint(8) NOT NULL auto_increment,
  `active` tinyint(1) NOT NULL default '0',
  `name` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `authorurl` varchar(255) NOT NULL,
  `description` tinytext NOT NULL,
  `dir` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL,
  PRIMARY KEY  (`plugin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk;
";

$sql_query[]="DROP TABLE IF EXISTS {$prefix}tag;";
$sql_query[]="
CREATE TABLE `{$prefix}tag` (
  `tag_id` mediumint(8) NOT NULL auto_increment,
  `name` varchar(60) NOT NULL,
  `urlname` varchar(255) NOT NULL,
  `blog_id` text NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY  (`tag_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk;
";

$sql_query[]="DROP TABLE IF EXISTS {$prefix}taotao;";
$sql_query[]="
CREATE TABLE `{$prefix}taotao` (
  `taotao_id` mediumint(8) NOT NULL auto_increment,
  `content` varchar(400) NOT NULL,
  `dateline` int(10) NOT NULL,
  `user_id` mediumint(8) NOT NULL default '-1',
  `ismobile` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`taotao_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk;
";


$sql_query[]="DROP TABLE IF EXISTS {$prefix}trackback;";
$sql_query[]="
CREATE TABLE `{$prefix}trackback` (
  `trackback_id` mediumint(8) NOT NULL auto_increment,
  `blog_id` mediumint(8) NOT NULL,
  `title` varchar(255) NOT NULL,
  `dateline` int(10) NOT NULL,
  `excerpt` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `blogname` varchar(255) NOT NULL,
  `ip` varchar(16) NOT NULL,
  PRIMARY KEY  (`trackback_id`),
  KEY `blog_id` (`blog_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk;
";

$sql_query[]="DROP TABLE IF EXISTS {$prefix}user;";
$sql_query[]="
CREATE TABLE IF NOT EXISTS `{$prefix}user` (
  `user_id` mediumint(8) unsigned NOT NULL auto_increment,
  `username` varchar(32) NOT NULL default '',
  `password` varchar(32) NOT NULL,
  `dateline` int(10) NOT NULL default '0',
  `usergroup` tinyint(1) NOT NULL default '0',
  `nikename` varchar(32) NOT NULL,
  `email` char(100) NOT NULL,
  `bloggerphoto` char(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `sex` tinyint(1) NOT NULL default '0',
  `age` tinyint(3) NOT NULL default '0',
  `work` text NOT NULL,
  `marry` tinyint(1) NOT NULL default '0',
  `love` text NOT NULL,
  `qq` bigint(20) NOT NULL default '0',
  `msn` text NOT NULL,
  `skype` text NOT NULL,
  `weyaoblog` text NOT NULL,
  `xiaonei` text NOT NULL,
  `homepage` text NOT NULL,
  `birthday` bigint(11) NOT NULL default '0',
  `school` text NOT NULL,
  `hometown` text NOT NULL,
  `nowplace` text NOT NULL,
  `logincount` mediumint(8) NOT NULL,
  `logintime` int(10) NOT NULL,
  `loginip` varchar(16) NOT NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk;
";

$date=time();
$sql_query[]="INSERT INTO `{$prefix}user` (`user_id`, `username`, `password`, `dateline`, `usergroup`, `nikename`, `email`, `bloggerphoto`, `description`, `sex`, `age`, `work`, `marry`, `love`, `qq`, `msn`, `skype`, `weyaoblog`, `xiaonei`, `homepage`, `birthday`, `school`, `hometown`, `nowplace`, `logincount`, `logintime`, `loginip`) VALUES
(1, '{$admin_username}', '{$admin_password}','$date', 1, 'D先生', 'log1990@126.com', '07686ef930b4c3837f7df800b871fd10-20100826142645.gif', 'Not TM ,But TMD.', 0, 19, '', 1, '', 123456789, '', '', '', '', 'http://www.doyouhaobaby.com', 0, 'a:4:{i:0;s:2:\"df\";i:1;s:2:\"fd\";i:2;s:11:\"fdddddddddd\";i:3;s:2:\"fd\";}', 'fd', 'fdddddddddddddd', 237, 1283853356, '127.0.0.1')
";

//循环写入数据
foreach($sql_query as $value){
   $DB->query($value);
}
    
//为了安全，程序自动删除安装文件
@unlink('install.php');
  echo "<script>alert('安装程序执行结束，即将跳转至首页。安装程序已经被程序自行删除了，当然为了安全请你检查一遍是否还存在。');top.location.href='index.php'</script>";	
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>DoYouHaoBaby博客--安装程序</title>
<meta http-equiv="content-type" content="application/xhtml+xml; charset=gbk" />
<link rel="stylesheet" href="images/common.css" type="text/css" />
</head>
<body>
<div id="main">
<h1>DoYou好?Baby-blog一键安装程序</h1>
<p style="text-align: center">致力于个人信息发布，允许高级的cms扩展！</p>
<form action="" method="post">
 <div>Mysql主机：<input type='text' name='localhost' value="localhost" ><br><br>
 Mysql用户：<input type='text' name='username' ><br><br>
 Mysql密码：<input type='password' name='password'><br><br>
 Mysql名称：<input type='text' name='dbname'><br><br>
 Mysql前缀：<input type='text' name='prefix' value="d_"><br><br>
 超级管理员：<input type='text' name='admin_username' value="admin"><br><br>
 后台密码：<input type='text' name='admin_password' value="123456"><br><br>
<input type=submit name=install value='  一键安装  '>
</form>
</div>
</body>
</html>