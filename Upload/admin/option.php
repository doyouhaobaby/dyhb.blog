<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：option.php
        * 说明：系统配置
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

CheckPermission("cp",$common_permission[24]);
//读取语言包中时间配置
$timelist = $admin_timelist;
$thread_comments_depth=array('2','3','4','5','6','7','8','9','10');
$side_tag_color=implode('|',unserialize($dyhb_options[side_tag_color]));
$qq_allowed_filetype=implode('|',unserialize($dyhb_options[qq_allowed_filetype]));
$all_allowed_filetype=implode('|',unserialize($dyhb_options[all_allowed_filetype]));
$icon_width_height=implode('|',unserialize($dyhb_options[icon_width_height]));
$all_width_height=implode('|',unserialize($dyhb_options[all_width_height]));
$is_leech_domail=implode('|',unserialize($dyhb_options[is_leech_domail]));
$wapfilelist_width_height=implode('|',unserialize($dyhb_options[wapfilelist_width_height]));
$wapfilesortlist_width_height=implode('|',unserialize($dyhb_options[wapfilesortlist_width_height]));
$wapfileshow_width_height=implode('|',unserialize($dyhb_options[wapfileshow_width_height]));
$blog_width_height=implode('|',unserialize($dyhb_options[blog_width_height]));
$blogicon_thumb_width_heigth=implode('|',unserialize($dyhb_options[blogicon_thumb_width_heigth]));
$blogfile_thumb_width_heigth=implode('|',unserialize($dyhb_options[blogfile_thumb_width_heigth]));
$protect_file_dir=implode('|',unserialize($dyhb_options[protect_file_dir]));
$Modellist=unserialize($dyhb_options['modellist']);
$Modellist_show=unserialize($dyhb_options['modellist_show']);
$Modellist_sort=unserialize($dyhb_options['modellist_sort']);
//博客页眉
$default_header_img_num="";
if($dyhb_options['default_header']!=''&&substr($dyhb_options['default_header'],'0','4')!='http'){
    $default_header_img_num=substr($dyhb_options['default_header'],'-5','1');
}
$default_header=$dyhb_options['default_header']!=''&&substr($dyhb_options['default_header'],'0','4')!='http'?"../".$dyhb_options['default_header']:$dyhb_options['default_header'];

$sendemail=unserialize( $dyhb_options['sendemail'] );

 //change
if($view=="save_basic"){
	//常规设置
	$blogtitle = sql_check( get_argpost('blogtitle') );
    $bloginfo =sql_check( get_argpost('bloginfo') ) ;
    $blogurl =sql_check( get_argpost('blogurl') ) ;
    $icp =sql_check( get_argpost('icp') ) ;
	$blog_is_open =intval( get_argpost('blog_is_open') );
    $gzipcompress =sql_check( get_argpost('gzipcompress') );
    $why_blog_close=sql_check( get_argpost('why_blog_close') );
	$timezone =intval( get_argpost('timezone') );
	$all_count_num =intval( get_argpost('all_count_num') );
	$today_count_num =intval( get_argpost('today_count_num') );
	$is_float_div_action=intval( get_argpost('is_float_div_action') );
	$blog_cms_bbs=intval( get_argpost('blog_cms_bbs') );
    $after_login_back=intval( get_argpost('after_login_back') );
	$blog_logo=sql_check( get_argpost('blog_logo') );
	$global_lang_select=sql_check( get_argpost('global_lang_select') );
	$is_outlink_check=intval( get_argpost('is_outlink_check') );

	//导航条排序
	$Daohang_a=array('tag','link','search','record','mp3','photo','microlog','guestbook');
	//模型名字，显示与否，排序
	$ModelList_sort=array();
	$ModelList_show=array();
	$ModelList=array();
	$ModelList_m=array();
	foreach($Daohang_a as $value){
	   $daohang_sort="daohang_".$value."_sort";
	   $daohang_show="daohang_".$value."_show";
	   $daohang_name="daohang_".$value;
	   $daohang_sort2=intval( get_argpost($daohang_sort) );
	   $daohang_name2=sql_check( get_argpost($daohang_name) );
	   $daohang_show2=intval( get_argpost($daohang_show) );
	   //排序，显示与否赋值
	   $ModelList_show[$value]=$daohang_show2;
	   $ModelList_sort[$value]=$daohang_sort2;
	   //判断是否存在相同的序号
	   if(count($ModelList_sort)!=count(array_unique($ModelList_sort))){
	      DyhbMessage("<font color='red'>$common_func[229]</font>","");
	   }
	   //前台模型
	   if($daohang_show2=="1"){
		   $the_infor=array($value,$daohang_name2);
	       $ModelList_m[$daohang_sort2]=$the_infor;
	   }
	}
	ksort($ModelList_m);
	foreach($ModelList_m as $value){
	   $ModelList[$value[0]]=$value['1'];
	}
    $ModelList=serialize($ModelList);
	$ModelList_show=serialize($ModelList_show);
	$ModelList_sort=serialize($ModelList_sort);

	//版权设置
	$prower_blog_name =sql_check( get_argpost('prower_blog_name') );
	$blog_program_url =sql_check( get_argpost('blog_program_url') );
	$option_date=array('2'=>$blogtitle,'3'=>$bloginfo,'28'=>$blogurl,'5'=>$icp,'42'=>$gzipcompress,'71'=>$blog_is_open,'73'=>$why_blog_close,'29'=>$timezone,'39'=>$all_count_num,'40'=>$today_count_num,'30'=>$prower_blog_name,'32'=>$blog_program_url,'31'=>$ModelList,'93'=>$ModelList_show,'96'=>$ModelList_sort,'113'=>$blog_logo,'161'=>$is_float_div_action,'162'=>$blog_cms_bbs,'163'=>$after_login_back,'166'=>$global_lang_select,'167'=>$is_outlink_check);
}

if($view=="save_display"){
	//常规设置
	$admin_log_num =intval( get_argpost('admin_log_num') );
    $admin_tag_num = intval( get_argpost('admin_tag_num') );
	$relatelog_num =intval( get_argpost('relatelog_num') ) ;
	$rss_log_num =intval( get_argpost('rss_log_num') ) ;
    $user_log_num =intval( get_argpost('user_log_num') ) ;
    $every_guest_num =intval( get_argpost('every_guest_num') ) ;
	$every_taotao_num =intval( get_argpost('every_taotao_num') );
	$default_view_list=intval( get_argpost('default_view_list') );
	$see_the_sortvalue=intval( get_argpost('see_the_sortvalue') );
    //边栏控制
    $hotlog_num =intval( get_argpost('hotlog_num') );
	$randlog_num =intval( get_argpost('randlog_num') ) ;
	$newlog_num = intval( get_argpost('newlog_num') );
    $year_hot_log_num =intval( get_argpost('year_hot_log_num') ) ;
    $mouth_hot_log_num =intval( get_argpost('mouth_hot_log_num') ) ;
	$comment_log_num =intval( get_argpost('comment_log_num') );
	$taotao_show_num =intval( get_argpost('taotao_show_num') ) ;
    $taotao_cut_num = intval( get_argpost('taotao_cut_num') );
    $side_tag_color =  serialize(explode('|',sql_check( get_argpost('side_tag_color') )));
	$hot_tag_num =intval( get_argpost('hot_tag_num') );
	$side_sort_tree=intval( get_argpost('side_sort_tree') );
    //日志控制
	$is_auto_cut =intval( get_argpost('is_auto_cut') );
	$auto_cut_num =intval( get_argpost('auto_cut_num') );
    $is_autosave =intval( get_argpost('is_autosave') );
	$enable_trackback =intval( get_argpost('enable_trackback') );
	$is_allow_tolocalimg =intval( get_argpost('is_allow_tolocalimg') );
	$cms_newlog_num = get_argpost('cms_newlog_num')  ;
	$cms_content_num =intval( get_argpost('cms_content_num') ) ;
	$cache_cms_log = intval( get_argpost('cache_cms_log') );
	//wap设置
	$wap_is_open =intval( get_argpost('wap_is_open') );
    $mobile_log_num=intval( get_argpost('mobile_log_num') );
    $mobile_tag_num =intval( get_argpost('mobile_tag_num') ) ;
    $mobile_comment_num =intval( get_argpost('mobile_comment_num') );
    $mobile_taotao_num =intval( get_argpost('mobile_taotao_num') ) ;
	$every_wap_file_num =intval( get_argpost('every_wap_file_num') ) ;
	$wapfilelist_width_height=  serialize(explode('|',sql_check( get_argpost('wapfilelist_width_height') )));
	$wapfilesortlist_width_height=  serialize(explode('|',sql_check( get_argpost('wapfilesortlist_width_height') )));
	$wapfileshow_width_height=  serialize(explode('|',sql_check( get_argpost('wapfileshow_width_height') )));
	$option_date=array('4'=>$user_log_num,'7'=>$admin_log_num,'21'=>$rss_log_num,'10'=>$relatelog_num,'58'=>$every_guest_num,'81'=>$admin_tag_num,'63'=>$every_taotao_num,'8'=>$hotlog_num,'9'=>$randlog_num,'11'=>$newlog_num,'35'=>$year_hot_log_num,'37'=>$mouth_hot_log_num,'34'=>$comment_log_num,'44'=>$taotao_show_num,'45'=>$taotao_cut_num,'49'=>$side_tag_color,'100'=>$hot_tag_num,'95'=>$is_allow_tolocalimg,'82'=>$enable_trackback,'90'=>$cache_cms_lo,'78'=>$is_auto_cut,'79'=>$auto_cut_num,'1'=>$is_autosave,'14'=>$cms_newlog_num,'46'=>$cms_content_num,'72'=>$wap_is_open,'74'=>$mobile_log_num,'75'=>$mobile_tag_num,'76'=>$mobile_comment_num,'77'=>$mobile_taotao_num,'90'=>$cache_cms_log,'132'=>$side_sort_tree,'133'=>$default_view_list,'134'=>$see_the_sortvalue,'145'=>$every_wap_file_num,'146'=>$wapfilelist_width_height,'147'=>$wapfilesortlist_width_height,'148'=>$wapfileshow_width_height);
}

if($view=="save_comment"){
	//常规
	$newcomment_num =intval( get_argpost('newcomment_num') ) ;
    $log_comment_num = intval( get_argpost('log_comment_num') );
    $index_comment_num = intval( get_argpost('index_comment_num') );
    $admin_comment_num = intval( get_argpost('admin_comment_num') );
    $new_guestbook_num =intval( get_argpost('new_guestbook_num') );
	$com_examine =intval( get_argpost('com_examine') ) ;
	$sendmail_telladmin =intval( get_argpost('sendmail_telladmin') );
	$sendmail_isreplymail =intval( get_argpost('sendmail_isreplymail') ) ;
	$sendemail=serialize( $_POST['sendemail'] );

	//显示
	$thread_comments_depth=intval( get_argpost('thread_comments_depth') );
	$commentdateformat=sql_check( get_argpost('commentdateformat') );
	$commentsrrlnofollow=sql_check( get_argpost('commentsrrlnofollow') );
	$show_avatars=sql_check( get_argpost('show_avatars') );
	$avatar_rating=sql_check( get_argpost('avatar_rating') );
	$avatar_default=sql_check( get_argpost('avatar_default') );
	$comment_avatars_size=intval( get_argpost('comment_avatars_size') );
	$option_date=array('12'=>$newcomment_num,'17'=>$log_comment_num,'43'=>$new_guestbook_num,'23'=>$index_comment_num,'24'=>$admin_comment_num,'84'=>$com_examine,'36'=>$thread_comments_depth,'102'=>$commentdateformat,'103'=>$commentsrrlnofollow,'104'=>$show_avatars,'105'=>$avatar_rating,'106'=>$avatar_default,'107'=>$comment_avatars_size,'136'=>$sendmail_telladmin,'137'=>$sendmail_isreplymail,'138'=>$sendemail);
}

if($view=="save_ad"){
  $ad_logo_beside =get_argpost('ad_logo_beside' );
  $ad_header =get_argpost('ad_header' );
  $ad_footer =get_argpost('ad_footer' );
  $ad_sidebar =get_argpost('ad_sidebar' );
  $ad_showlog =get_argpost('ad_showlog' );
  $ad_other =get_argpost('ad_other' );
  $blogkeyword =sql_check( get_argpost('blogkeyword') ) ;
  $blogdescription =sql_check( get_argpost('blogdescription') ) ;
  $otherhead =get_argpost('otherhead') ;
  $visitor_count_html =get_argpost('visitor_count_html' );
  $option_date=array('13'=>$ad_logo_beside,'15'=>$ad_header,'16'=>$ad_footer,'19'=>$ad_sidebar,'20'=>$ad_showlog,'26'=>$blogkeyword,'25'=>$blogdescription,'27'=>$otherhead,'97'=>$visitor_count_html);
}

if($view=="save_file"){
   $file_store_bydate =intval( get_argpost('file_store_bydate') );
   $every_file_num = intval( get_argpost('every_file_num') );
   $every_photo_num= intval( get_argpost('every_photo_num') );
   $uploadfile_maxsize =get_argpost('uploadfile_maxsize');
   $icon_width_height=  serialize(explode('|',sql_check( get_argpost('icon_width_height') )));
   $all_width_height =  serialize(explode('|',sql_check( get_argpost('all_width_height') ) ));
   $qq_allowed_filetype = serialize(explode('|',sql_check( get_argpost('qq_allowed_filetype') )));
   $all_allowed_filetype = serialize(explode('|',sql_check( get_argpost('all_allowed_filetype') )));
   $new_music_num= intval( get_argpost('new_music_num') );
   $new_photo_num= intval( get_argpost('new_photo_num') );
   $is_image_leech= intval( get_argpost('is_image_leech') );
   $is_image_inline= intval( get_argpost('is_image_inline') );
   $is_leech_domail= serialize(explode('|',sql_check( get_argpost('is_leech_domail') )));
   $file_input_num = intval( get_argpost('file_input_num') );
   $upload_file_default =intval ( get_argpost('upload_file_default') );
   $flash_log_num = intval( get_argpost('flash_log_num') );
   $show_flash_log =intval ( get_argpost('show_flash_log') );
   $photo_isshow_hide= intval ( get_argpost('photo_isshow_hide') );
   $is_oneplay_default= intval ( get_argpost('is_oneplay_default') );
   $blog_width_height=  serialize(explode('|',sql_check( get_argpost('blog_width_height') )));
   $blogfile_thumb_width_heigth =  serialize(explode('|',sql_check( get_argpost('blogfile_thumb_width_heigth') ) ));
   $blogicon_thumb_width_heigth =  serialize(explode('|',sql_check( get_argpost('blogicon_thumb_width_heigth') ) ));
   $is_makeimage_thumb= intval ( get_argpost('is_makeimage_thumb') );
   $is_images_water_mark= intval ( get_argpost('is_images_water_mark') );
   $images_water_type =get_argpost('images_water_type');
   $images_water_position =get_argpost('images_water_position');
   $images_water_mark_img_imgurl =get_argpost('images_water_mark_img_imgurl');
   $images_water_mark_text_content =get_argpost('images_water_mark_text_content');
   $images_water_mark_text_fontsize =get_argpost('images_water_mark_text_fontsize');
   $images_water_mark_text_color="#".sql_check( get_argpost('images_water_mark_text_color') );
   $thumb_is_water_mark= intval ( get_argpost('thumb_is_water_mark') );
   $is_jquery_effects= intval ( get_argpost('is_jquery_effects') );

   $option_date=array('55'=>$file_store_bydate,'50'=>$uploadfile_maxsize,'52'=>$qq_allowed_filetype,'51'=>$all_allowed_filetype,'59'=>$every_photo_num,'56'=>$every_file_num,'53'=>$icon_width_height,'54'=>$all_width_height,'98'=>$new_music_num,'99'=>$new_photo_num,'22'=>$is_image_leech,'33'=>$is_image_inline,'80'=>$is_leech_domail,'139'=>$upload_file_default,'140'=>$file_input_num,'141'=>$flash_log_num,'142'=>$show_flash_log,'143'=>$photo_isshow_hide,'144'=>$is_oneplay_default,'149'=>$blog_width_height,'150'=>$is_makeimage_thumb,'151'=>$blogfile_thumb_width_heigth,'152'=>$is_images_water_mark,'153'=>$images_water_type,'154'=>$images_water_position,'155'=>$images_water_mark_img_imgurl,'156'=>$images_water_mark_text_content,'157'=>$images_water_mark_text_color,'158'=>$images_water_mark_text_fontsize,'159'=>$blogicon_thumb_width_heigth,'160'=>$thumb_is_water_mark,'168'=>$is_jquery_effects);
}

if($view=="save_safe"){
    $admin_code = intval( get_argpost('admin_code') );
	$user_code =intval( get_argpost('user_code') );
	$allowed_shenghe =intval( get_argpost('allowed_shenghe') );
	$allowed_send =intval( get_argpost('allowed_send') );
	$register_code = intval( get_argpost('register_code') );
	$allowed_register=intval( get_argpost('allowed_register') );
	$protect_file_dir =  serialize(explode('|',sql_check( get_argpost('protect_file_dir') )));
	$option_date=array('65'=>$admin_code,'64'=>$user_code,'92'=>$allowed_shenghe,'91'=>$allowed_send,'89'=>$register_code,'130'=>$allowed_register,'165'=>$protect_file_dir);
}

if($view=="save_link"){
	$tag_base =  get_argpost('tag_base') ;
	$url_chinese_english = intval( get_argpost('url_chinese_english') );
	$sort_base = get_argpost('sort_base');
	if($tag_base&&!preg_match('/^[a-z0-9\-\_]*[a-z\-_]+[a-z0-9\-\_]*$/i',$tag_base)){
	     DyhbMessage("$common_func[230]",'-1');
	}
	if($sort_base&&!preg_match('/^[a-z0-9\-\_]*[a-z\-_]+[a-z0-9\-\_]*$/i',$sort_base)){
	     DyhbMessage("$common_func[231]",'-1');
	}
	$permalink_structure=get_argpost('permalink_structure');
	$custom_permalink_structure =get_argpost('custom_permalink_structure') ;
	if($custom_permalink_structure!=""&&$custom_permalink_structure!="default"&&$custom_permalink_structure!="{y}/{m}/{d}/{post_name}/"&&$custom_permalink_structure!="{y}/{m}/{post_name}/"&&$custom_permalink_structure!="archives/{blog_id}/"){
	     $permalink_structure=$custom_permalink_structure;
	}elseif(!$permalink_structure){
	    $permalink_structure="default";
	}
	$allowed_make_html =intval( get_argpost('allowed_make_html') );
	$permalink_style=intval( get_argpost( 'permalink_style' ) );
	/** 写入ata文件 */
	if($permalink_structure!='default'){
	    if(!MakeRewrite(true,$permalink_style)){
			//DyhbMessage("不能写入.hta",'');
            $permalink_structure!='default';
		}
	}else{
	    MakeRewrite(false,'');	
	}
	if($allowed_make_html=='1'){
	    MakeRewrite(false,'');
	}
	$option_date=array('83'=>$tag_base,'85'=>$sort_base,'101'=>$permalink_structure,'48'=>$allowed_make_html,'135'=>$permalink_style,'164'=>$url_chinese_english);
}

if($_POST[ok]){
	//更新
	foreach($option_date as $key=>$value){
	  $_Cools->UpdateCools($value,$key);
    }
	unset( $option_date );
    //缓存
    CacheOptions();
	$url=substr($view,'5');
    header("location:?action=option&do={$url}&upd=true");
}

if($view=='ad'||$view=='link'){
    IsSuperAdmin($common_permission[25],'');
}

include DyhbView('option',1);

?>