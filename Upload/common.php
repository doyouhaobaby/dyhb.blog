<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���common.php
        * ˵����ǰ̨����
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

/** ���غ��Ĳ��� */
require_once('width.php');

/** �������԰� */
require_once(DOYOUHAOBABY_ROOT."/images/lang/$dyhb_options[global_lang_select]/front.php");

/** ����״̬���,�Ƿ������� */
if($dyhb_options['blog_is_open']=='0'){
	DyhbMessage($dyhb_options['why_blog_close'],'0');
}

/** ����ģ����������� */
include DyhbView('width','');

/**
 * ǰ̨����TPL
 * bug:ǰ̨����ģ�壬�����ܼ��ظ�ģ��Ļ��������⣬ֻ�ܼ��ص�ǰʹ�õ�ģ�壬���ⲻӰ���������,��Ϊģ�����������
 *
 */
$TplName = CheckSql(sql_check(get_args('tpl')));
if($TplName){
	 set_cookie('tpl',$TplName,'86400');
	 header("location:"."./");
}

/** ����ͳ�� **/
visitor();

/**
 * ����ǰ̨���û����ļ������ڼӿ���������ٶ�
 * ˵����������Ҫ��׼�Բ�����ģ���������Ĳ�������ݶ������˻��棬��ȻҲ��������������
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
/** cms���ݵ��û������ݶ�ȡ */
$CmsNew=ReadCache('cms_newlog');
$CmsNew1=$CmsNew[0];
$CmsNew2=$CmsNew[1];
//����
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
 * ������Ĳ��������ؼ�����
 * ˵����������������أ��������õ����ݻ�ȡ�����֧��4������
 *
 */
$_sideName=unserialize($dyhb_options['sidebar_widget_name']);//name
$_sideSort=unserialize($dyhb_options['sidebar_widget_sort']);//����
$_sideSort2=unserialize($dyhb_options['sidebar_widget_sort2']);
$_sideSort3=unserialize($dyhb_options['sidebar_widget_sort3']);
$_sideSort4=unserialize($dyhb_options['sidebar_widget_sort4']);
$_sideShow=unserialize($dyhb_options['sidebar_widget_show']);//��ʾ���
$_sideShow2=unserialize($dyhb_options['sidebar_widget_show2']);
$_sideShow3=unserialize($dyhb_options['sidebar_widget_show3']);
$_sideShow4=unserialize($dyhb_options['sidebar_widget_show4']);



/** ǰ̨ģ�崦������ */
require_once(DOYOUHAOBABY_ROOT.'/base.func/front.tpl.func.php');

/**
 * �Զ���url�ν����ݴ���
 * ˵����$_UrlIs_xx���ж��Ǳ�ǩ�����࣬�浵�ȵ����ͣ�ͨ�������α��̬URL������������
 * $Common_url��α��̬�������ص����ݣ���Ҫ�����ڲ�ѯ���ݿ�
 $ $View�û�������α��̬���ݴ���
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
 *����ǰ̨ģ�崦������front.tpl.func.php����ͬ�ķ�ʽ�����ֹ���������,�Ա����Լ��ķ�ʽͳһ������� 
 *���֣�$sidebar_xxxx;
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

/** ��־�б��ڴ�ͳ֮���໥�л� **/
$view_way=isset($_GET['way'])? $_GET['way']:'';
if($view_way=='list'){
	   set_cookie('way','list','86400');
	   header("location:".$_SERVER['HTTP_REFERER']);
   }elseif($view_way=='narmal'){
	   set_cookie('way','narmal','86400');
	   header("location:".$_SERVER['HTTP_REFERER']);
}

?>