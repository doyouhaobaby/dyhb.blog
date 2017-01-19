<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：common.php
        * 说明：前台公用
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

/** 加载核心部件 */
require_once('width.php');

/** 加载语言包 */
require_once(DOYOUHAOBABY_ROOT."/images/lang/$dyhb_options[global_lang_select]/front.php");

/** 博客状态检查,是否开启访问 */
if($dyhb_options['blog_is_open']=='0'){
	DyhbMessage($dyhb_options['why_blog_close'],'0');
}

/** 加载模板基础函数库 */
include DyhbView('width','');

/**
 * 前台更换TPL
 * bug:前台更换模板，好像不能加载该模板的基础函数库，只能加载当前使用的模板，当这不影响程序运行,因为模板基础函数库
 *
 */
$TplName = CheckSql(sql_check(get_args('tpl')));
if($TplName){
	 set_cookie('tpl',$TplName,'86400');
	 header("location:"."./");
}

/** 访问统计 **/
visitor();

/**
 * 程序前台所用缓存文件，用于加快程序运行速度
 * 说明：缓存主要是准对侧边栏的，程序大量的侧边栏数据都进行了缓存，当然也包括其它的数据
 *
 */
$PageNav=ReadCache('side_pagenav');
$_flashLog=ReadCache('flash_log');
$_sideNewlog=ReadCache('side_newlog');
$_sideHotlog=ReadCache('side_hotlog');
$_sideCommentlog=ReadCache('side_commentlog');
$_sideYearHotLog=ReadCache('side_yearhotlog');
$_sideMouthHotLog=ReadCache('side_mouthhotlog');;
$_sideRandlog=ReadCache('side_randlog');
$_sideTaotao=ReadCache('side_taotao');
$_sideNewcomment=ReadCache('side_newcomment');
$_sideNewguestbook=ReadCache('side_newguestbook');
$_sideNewmusic=ReadCache('side_newmusic');
$_sideNewphoto=ReadCache('side_newphoto');
$plugin_navbar=ReadCache('plugin_navbar');
/** cms数据调用缓存数据读取 */
$CmsNew=ReadCache('cms_newlog');
$CmsNew1=$CmsNew[0];
$CmsNew2=$CmsNew[1];
//分类
if(!empty($_sideSorts)){
    foreach($_sideSorts as $value){
	     $_CmsBigSort=ReadCache('cms_bigsort_'.$value[sort_id]);
	     $CmsSortName="CmsSortName".$value[sort_id];
	     $$CmsSortName=$_CmsBigSort[0];
         $CmsSort1="CmsSort1_".$value[sort_id];
	     $CmsSort2="CmsSort2_".$value[sort_id];
	     $$CmsSort1=$_CmsBigSort[1];
	     $$CmsSort2=$_CmsBigSort[2];
    }
}


/**
 * 本程序的侧边栏处理关键数据
 * 说明：侧边栏排序，隐藏，名字设置等数据获取，最大支持4组侧边栏
 *
 */
$_sideName=unserialize($dyhb_options['sidebar_widget_name']);//name
$_sideSort=unserialize($dyhb_options['sidebar_widget_sort']);//排序
$_sideSort2=unserialize($dyhb_options['sidebar_widget_sort2']);
$_sideSort3=unserialize($dyhb_options['sidebar_widget_sort3']);
$_sideSort4=unserialize($dyhb_options['sidebar_widget_sort4']);
$_sideShow=unserialize($dyhb_options['sidebar_widget_show']);//显示与否
$_sideShow2=unserialize($dyhb_options['sidebar_widget_show2']);
$_sideShow3=unserialize($dyhb_options['sidebar_widget_show3']);
$_sideShow4=unserialize($dyhb_options['sidebar_widget_show4']);



/** 前台模板处理处理函数 */
require_once(DOYOUHAOBABY_ROOT.'/base.func/front.tpl.func.php');

/**
 * 自定义url衔接数据处理
 * 说明：$_UrlIs_xx是判断是标签，分类，存档等等类型，通过下面的伪静态URL分析函数决定
 * $Common_url是伪静态函数返回的数据，主要是用于查询数据库
 $ $View用户导航条伪静态数据处理
 *
 */
$_UrlIsPage=false;
$_UrlIsPlugin=false;
$_UrlIsCategory=false;
$_UrlIsRecord=false;
$_UrlIsTag=false;
$_UrlIsAuthor=false;
$_UrlIsBlog=false;
$_UrlIsPagenav=false;
$Common_url=url_analyse();
if($dyhb_options['permalink_structure']!='default'&&$_UrlIsPagenav){
	 $View=$Common_url;
}

/** 
 *调用前台模板处理函数，front.tpl.func.php以相同的方式和名字规则处理数据,以便于以简洁的方式统一输出数据 
 *名字：$sidebar_xxxx;
 *
 */
$sidebar_sort=_side_sort();
$sidebar_login=_side_login();
$sidebar_microlog=_side_microlog();
$sidebar_photosort=_side_photosort();
$sidebar_mp3sort=_side_mp3sort();
$sidebar_hottag=_side_hottag();
$sidebar_record=_side_record();
$sidebar_guestbook=_side_guestbook();
$sidebar_comment=_side_comment();
$sidebar_hotlog=_side_hotlog();
$sidebar_yearlog=_side_yearlog();
$sidebar_mouthlog=_side_mouthlog();
$sidebar_tpl=_side_tpl();
$sidebar_commentlog=_side_commentlog();
$sidebar_newlog=_side_newlog();
$sidebar_randlog=_side_randlog();
$sidebar_link=_side_link();
$sidebar_music=_side_newmusic();
$sidebar_theblog=_side_theblog();
$sidebar_blogger=_side_blogger();
$sidebar_calendar=Calendar($localdate);

/** 日志列表于传统之间相互切换 **/
$view_way=isset($_GET['way'])? $_GET['way']:'';
if($view_way=='list'){
	   set_cookie('way','list','86400');
	   header("location:".$_SERVER['HTTP_REFERER']);
   }elseif($view_way=='narmal'){
	   set_cookie('way','narmal','86400');
	   header("location:".$_SERVER['HTTP_REFERER']);
}

?>