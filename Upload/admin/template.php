<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���template.php
        * ˵����ģ�����
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

CheckPermission("cp",$common_permission[20]);

//ģ�����
/**
  * ģ���б�
  *
  * @param string $dir ģ��·��
  * @return string
  */
function listDir($dir,$isadmin){
 $templateinfo="";
 if(is_dir($dir)){
   if ($dh = opendir($dir)) {
   while (($file= readdir($dh)) !== false){
   if((is_dir($dir."/".$file)) && $file!="." && $file!=".."){
	if($isadmin){
	  $templateinfo.="
	  <li>
	  <a href=\"index.php?action=template&do=admin_template&name=".$file."\">
      <img src=\"".$dir.$file."/helloworld.png\" title=\"$common_func[198]".$file."$common_func[199]\"/>
      </a>
	 </li>";
	}else{
	  $templateinfo.="
	  <li>
	  <a href=\"index.php?action=template&do=user_template&name=".$file."\">
      <img src=\"".$dir.$file."/helloworld.png\" title=\"$common_func[198]".$file."$common_func[199]\"/>
      </a>
	  </li>";
	 }
	}
   }
   closedir($dh);
  }
 }
 return $templateinfo;
}

//��ȡģ��·��,���ú������ǰ̨ģ�����
if($view!="admin"){
   $templatedir="../view/";
}else{
   $templatedir="view/";
   //��̨ģ�����������
   //������ã���Ϊ���滻��ǰ̨ģ�������������ֵ
	$width_file='view/'.$dyhb_options['admin_template'].'/width.php';
	if(file_exists($width_file)){
	     require_once('view/'.$dyhb_options['admin_template'].'/width.php');
	 }
	/*else{
	     require_once("view/default/width.php");
	}*/
}
$isadmin=$view=="admin"?true:false;
$templateinfo=listDir($templatedir,$isadmin);

//ǰ̨ģ��
if($view=="user_template"){
    $name=sql_check( get_argget('name') );
    if($name){ $_Cools->UpdateCools($name,'6');}
	CacheOptions();
    header("location:?action=template&ut_upd=true");
}

//��̨ģ��
if($view=="admin_template"){
   $name=sql_check( get_argget('name') );
   if($name){ $_Cools->UpdateCools($name,'47');}
   CacheOptions();
   header("location:?action=template&do=admin&at_upd=true");
}

//�������
if( $view=="blogcover" ){
     //����ҳü
     $default_header_img_num="";
    if($dyhb_options['default_header']!=''&&substr($dyhb_options['default_header'],'0','4')!='http'){
         $default_header_img_num=substr($dyhb_options['default_header'],'-5','1');
    }
    $default_header=$dyhb_options['default_header']!=''&&substr($dyhb_options['default_header'],'0','4')!='http'?"../".$dyhb_options['default_header']:$dyhb_options['default_header'];
}

//������۱���
if( $view=="saveblogcover" ){
    //�������
    $default_header_img=sql_check( get_argpost('default_header_img') );
	$default_header=sql_check( get_argpost('default_header') );
	$blog_background_color="#".sql_check( get_argpost('blog_background_color') );
	$blog_background_img=sql_check( get_argpost('blog_background_img') );
	$blog_background_img_wide=sql_check( get_argpost('blog_background_img_wide') );
	$blog_background_attachment=sql_check( get_argpost('blog_background_attachment') );
	$is_show_blog_cover=intval( get_argpost('is_show_blog_cover') );
    if($default_header_img!=""&&substr($default_header_img,'0','4')=='http'){
	     $default_header=$default_header_img;
	}elseif(!$default_header){
	    $default_header="images/template/header_default_thumb_1.jpg";
	}
	$blog_top_img_or_word=sql_check( get_argpost('blog_top_img_or_word') );
	$option_date=array('108'=>$default_header,'110'=>$blog_background_color,'111'=>$blog_background_img,'112'=>$blog_background_img_wide,'114'=>$blog_background_attachment,'109'=>$blog_global_word_color,'120'=>$blog_top_img_or_word,'169'=>$is_show_blog_cover);
	//����
	foreach($option_date as $key=>$value){
	  $_Cools->UpdateCools($value,$key);
    }
	unset( $option_date );
    //����
    CacheOptions();
	header("location:?action=template&do=blogcover&cover=true");
}

//widget
if($view=='widget'){
    $widgetnum = intval( get_argget('widnum'));//����ID�޸Ĳ�ͬ�Ĳ����
	//���԰�������
    $SystemName=$admin_widget;
    //����
    $_widgetName=unserialize($dyhb_options['sidebar_widget_name']);

    switch($widgetnum){
       case "2":
       $_widgetSort=unserialize($dyhb_options['sidebar_widget_sort2']);//�Ƿ���ʾ
       $_widgetShow=unserialize($dyhb_options['sidebar_widget_show2']);//����
       break;
       case "3":
       $_widgetSort=unserialize($dyhb_options['sidebar_widget_sort3']);
       $_widgetShow=unserialize($dyhb_options['sidebar_widget_show3']);
       break;
       case "4":
       $_widgetSort=unserialize($dyhb_options['sidebar_widget_sort4']);
       $_widgetShow=unserialize($dyhb_options['sidebar_widget_show4']);
       break;
       default:
       $_widgetSort=unserialize($dyhb_options['sidebar_widget_sort']);
       $_widgetShow=unserialize($dyhb_options['sidebar_widget_show']);
       break;
     }
     $widget_button=$widgetnum==''?'':"&widnum=$widgetnum";//�ύ��ť;
     $_widgets=array();
     $widget_act=sql_check( get_argget('widget') );
     if($widget_act){
        $widget_name= sql_check(CheckSql(get_argpost($widget_act.'_name')));//����
        $widget_sort=intval( get_argpost($widget_act.'_sort'));//����
        $widget_show=intval( get_argpost($widget_act.'_show'));//�Ƿ���
	    //����
        $_widgetName[$widget_act]=$widget_name;
        $_widgetSort[$widget_act]=$widget_sort;
        $_widgetShow[$widget_act]=$widget_show;
        $_widgetName=serialize($_widgetName);
        $_widgetSort=serialize($_widgetSort);
	    $_widgetShow=serialize($_widgetShow);
	    $_Cools->UpdateCools($_widgetName,'60');
	    switch($widgetnum){
           case "2":
	       $_Cools->UpdateCools($_widgetSort,'18');
	       $_Cools->UpdateCools($_widgetShow,'66');
	       break;
	       case "3":
	       $_Cools->UpdateCools($_widgetSort,'69');
	       $_Cools->UpdateCools($_widgetShow,'67');
	       break;
	       case "4":
	       $_Cools->UpdateCools($_widgetSort,'70');
	       $_Cools->UpdateCools($_widgetShow,'68');
	       break;
	       default:
	       $_Cools->UpdateCools($_widgetSort,'61');
	       $_Cools->UpdateCools($_widgetShow,'62');
	       break;
        }
	   CacheOptions();
       Header("location:?action=template&do=widget&widnum={$widgetnum}&updwidget=true");
    }
}

//��ʼ��widget��������
if($view=='rebuild'){
	    //widget����
		//���԰���
        $_widgetName=$admin_widget;
	   //widget����
       $_widgetSort=array ('search'=> '0','blogger' => '1','login' => '2','calendar' => '3','microlog' => '4','sort' => '5','record' => '6','comment' => '7' ,'guestbook' => '8', 'hottag' => '9','newlog' => '10','randlog' => '11','hotlog' => '12','commentlog' => '13', 'yearlog' => '14','mouthlog' => '15','mp3sort' => '16','music' => '17','mp3' => '18','photosort' => '19','newphoto'=>'20','tpl' => '21','link' => '22','theblog' => '23');
        //widget��ʾ
        $_widgetShow=array ('search'=> '1','blogger' => '1','login' => '1','calendar' => '0','microlog' => '0','sort' => '1','record' => '1','comment' => '0' ,'guestbook' => '0', 'hottag' => '0','newlog' => '1','randlog' => '0','hotlog' => '0','commentlog' => '0', 'yearlog' => '0','mouthlog' => '0','mp3sort' => '0','music' => '0','mp3' => '0','photosort' => '0','newphoto'=>'0','tpl' => '0','link' => '1','theblog' => '1');
        $_widgetName=serialize($_widgetName);
	    $_Cools->UpdateCools($_widgetName,'60');
        $_widgetSort=serialize($_widgetSort);
	    $_widgetShow=serialize($_widgetShow);
	    $_Cools->UpdateCools($_widgetSort,'18');
	    $_Cools->UpdateCools($_widgetShow,'66');
	    $_Cools->UpdateCools($_widgetSort,'69');
	    $_Cools->UpdateCools($_widgetShow,'67');
	    $_Cools->UpdateCools($_widgetSort,'70');
	    $_Cools->UpdateCools($_widgetShow,'68');
	    $_Cools->UpdateCools($_widgetSort,'61');
	    $_Cools->UpdateCools($_widgetShow,'62');
	    CacheOptions();
        Header("location:?action=template&do=widget&rebulidwidget=true");
  }

//���
if($view=='plugin'){
   #
}

include DyhbView('template',1);

 ?>