<?php
/**================[^_^]================**\
      ---- ��Ϊ���Σ�������Ŀ�� ----
@----------------------------------------@
        * �ļ���index.php
        * ˵����ǰ̨������
        * ���ߣ�Сţ��
        * ʱ�䣺2010-05-06 20:22
        * �汾��DoYouHaoBaby-blog �����
        * ��ҳ��www.doyouhaobaby.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

/** ���غ��Ĳ��� */
require_once('common.php');
/** ��һ��� url��ֵ */
$BlogId = intval( get_argget('p') );
$CoolId = intval( get_argget('c') );
$Plugin = sql_check(CheckSql( get_args('plugin') ) );

/** ���ۼ�ס��Ϣ֮cookie */
$_Comment_name=get_cookie('commentname');
$_Comment_url=get_cookie('commenturl');
$_Comment_email=get_cookie('commentemail');


/**
 * ��ѯ��־�б�����
 * �����࣬��ǩ���鵵
 * $_UrlIs_XX����α��̬�жϣ���������������
 * $Common_url �������α��̬�Ļ�����ô��α��̬URL�������ݷ��ػ�����ѯ���ݿ�
 *
 */
if($View == ''&&!$BlogId&&!$Mp3Id&&!$CoolId&&!$Plugin&&!$ParentSortId&&!$_UrlIsPage&&!$_UrlIsPlugin&&!$_UrlIsPagenav&&!$_UrlIsBlog){
	/** ��ʼ������ */
    $record = intval( get_args('r') );
    $tag = sql_check(CheckSql( get_args('t') )) ;
    $sortid = intval( get_args('s') );
    $userid = intval( get_args('u') ) ;
    $theurl='';
    /** ������־Ȩ������ */
    if(CheckPermission("seehiddenentry",$common_permission[51],'0')&&!$isAdmin){
     	$isshow="";
     }else{
     	$isshow="and `isshow`='1'" ;
     }

    /** ͨ����ǩ������־*/
    if($tag||$_UrlIsTag){
    	if($dyhb_options['permalink_structure']!='default'&&$_UrlIsTag){
    		$tag=$Common_url;
    	}
	    $BlogIdStr=$_Tags->GetBlog_idStrByName($tag);
        if($BlogIdStr === false){
	        page_not_found();
        }
		//��ǩ��Ϣ�����ڱ�ǩ���ҵ����ݵĵ�����
        $tag_infor=$_Tags->GetOneTagByName($tag);

        $Sql="and `blog_id` in (".$BlogIdStr.") $isshow ORDER BY `istop` DESC,`dateline` DESC";
        $TotalLogNum=$DB->getresultnum("SELECT count(blog_id) FROM `".DB_PREFIX."blog` where `blog_id` in (".$BlogIdStr.") and `isshow`='1' and `ispage`='0'");
		$theurl=get_rewrite_tag($tag_infor);
   }
   /** ͨ�����������־ */
   elseif($sortid||$_UrlIsCategory){
	  if($dyhb_options['permalink_structure']!='default'&&$_UrlIsCategory){
		 //�������α��̬������ֵΪdefault����ô�������Ϊδ���࣬��־��sort_idΪ-1
	 	 if($Common_url!='default'){
	 	    $thesort=$DB->getonerow("select *from `".DB_PREFIX."sort` where `name`='$Common_url' OR `urlname`='$Common_url'");
		     $sortid=$thesort['sort_id'];
	 	 }else{
	 	 	 $sortid='-1';
	 	 }
	 }

	 /**
	  * ��ѯ����ĸ�������ӷ��࣬����������α��̬�༶����ĵ�������URL���������ӣ��ҵĹ���/Сѧ����/��ֻ�Ǹ���˵ ��
	  * ��������һ����ά���飬��ֵsort������ڣ���ֵΪһ����λ����
	  */
	 if($sortid!='-1'&&$sortid!=''){
		 //��ǰ���࣬���׷��࣬�ӷ���
         $ListSort=$_Sorts->GetIdSort($sortid);
	     $Loglist_parsort=$_Sorts->GetThreePar($ListSort);//������
	     $Loglist_child="";
	     if($ListSort[now]!="3"){
		     $Loglist_child=$_Sorts->GetParSort($ListSort['sort_id']);
			 if($Loglist_child){
			    foreach($Loglist_child as $key=>$value){
				    $Loglist_child[$key]['sort']=array($ListSort,$value);
				}
			 }
	     }
	  }else{
		  $Loglist_parsort['sort']=array(array('sort_id'=>'-1','name'=>$common_width[60],'urlname'=>'default','now'=>'1'));
	  }
      $Sql="and `sort_id`='$sortid' ORDER BY `istop` DESC,`dateline` DESC";
      $TotalLogNum=$DB->getresultnum("SELECT count(blog_id) FROM `".DB_PREFIX."blog` where `sort_id`='$sortid' $isshow and `ispage`='0'");
	  $theurl=get_rewrite_sort($Loglist_parsort[sort]);
  }
  /** ͨ���û�������־ */
  elseif($userid||$_UrlIsAuthor||$userid=='-1'){
  	 if($dyhb_options['permalink_structure']!='default'&&$_UrlIsAuthor){
		 if( $Common_url!='guest' ){
  	 	     $Sql="and `username`='$Common_url' ORDER BY `istop` DESC,`dateline` DESC";
  	 	     $the_user=$DB->getonerow("select `user_id` from `".DB_PREFIX."user` where `username`='$Common_url'");
			 $userid=$the_user['user_id'];
		 }else{
		     $userid='-1';
		 }	 	 
  	 }
	 /** $sort_userinfo �������û���Ϣ */
	 if( $userid!='-1' ){
	     $the_author=$_Cools->GetBloggerInfo($userid);
	     $nikename_c=$the_auther['nikename']?$the_author['nikename']:$the_author['username'];
		 $really_username=$the_author['username'];
     }else{
	     $nikename_c=$common_width[61];
		 $really_username="guest";
	 }
	 $sort_userinfo=array('user_id'=>$userid,'reallyuser'=>$really_username,'user'=>$nikename_c);
  	 $Sql="and `user_id`=".$userid ."\nORDER BY `istop` DESC,`dateline` DESC";
     $TotalLogNum=$DB->getresultnum("SELECT count(blog_id) FROM `".DB_PREFIX."blog` where `user_id`='$userid' $isshow and `ispage`='0'");
	 $url=get_rewrite_author($sort_userinfo);
  }
  /** ͨ���鵵������־ */
  elseif($record||$_UrlIsRecord){
  	 if($dyhb_options['permalink_structure']!='default'&&$_UrlIsRecord){
  	 	 $record=$Common_url;
  	 }
     /** ��־�鵵��record��*/
	 $the_year=substr($record,'0','4');
	 $the_mouth=substr($record,'4','2');

     $SqlTime=dyhb_date($record);
     $Sql="and `dateline` between\n" .$SqlTime[0]."\nand\n".$SqlTime[1]." $isshow order by `istop` desc,`dateline` desc";
     $TotalLogNum=$DB->getresultnum("SELECT count(blog_id) FROM `".DB_PREFIX."blog` where `dateline` between\n".$SqlTime[0]."\nand\n".$SqlTime[1]."\nand `isshow`='1' and `ispage`='0'");
	 //û������
	 //$theurl=get_rewrite_record($the_year,$the_mouth);
  }

  /** ��ҳ������־�б� */
  elseif(!$record&&!$userid&&!$sortid&&!$tag&&!$_UrlIsCategory&&!$_UrlIsRecord&&!$_UrlIsTag&&!$_UrlIsAuthor){
      $Sql="order by `istop` desc,`dateline` desc";
      $TotalLogNum=$DB->getresultnum("SELECT count(blog_id) FROM ".DB_PREFIX."blog where `ispage`='0' $isshow");
      $theurl="";

  }

  /** ��������������ѯ���ݿ⣬������־�б����� */
  if($TotalLogNum){
       Page($TotalLogNum,$dyhb_options['user_log_num'],$theurl);
       $_Loglist=$_Logs->GetLog($Sql,$pagestart,$dyhb_options['user_log_num'],$show='1',$ispage='0');
  }

  /** ����ģ���ļ��������־�б����ݣ����������Ĭ��ģ���Ƿ��б���ʾ */
  if(($_GET['way']&&$_GET['way']=='list')||get_cookie('way')=='list'||($dyhb_options['default_view_list']=='1'&&get_cookie('way')==''&&$_GET['way']=='')||($dyhb_options['blog_cms_bbs']=='3'&&$sortid&&get_cookie('way')!='narmal')||($sortid && $_GET['way']=='quicklog')){
	   include DyhbView('list','');
   }else{
	   if($record||$tag||$key||$userid){
           include DyhbView('xedni','');
	   }
	   elseif($sortid){
	       if($ListSort['style']=='1'||$sortid=='-1'){
                   include DyhbView('xedni','');
		  }else{
		           include DyhbView('sort'.$ListSort['style'],'');
		  }
	  }
	 else{
		  if($dyhb_options['blog_cms_bbs']=='2'&&(!$page||$page=='1')){
	          include DyhbView('index','');
		  }elseif($dyhb_options['blog_cms_bbs']=='3'&&(!$page||$page=='1')){
		      include DyhbView('bbs','');
		  }else{
		      include DyhbView('xedni','');
		  }
	   }
    }
}


/**
 * �����־(���������־)
 * $newpage_c��־��ҳ��������־��ҳ����ʹ��
 * $page ���۷�ҳ����
 */
$newpage = intval( get_args('newpage') );
$newpage_c=$newpage?$newpage:'1';
$compage_c=$page?$page:'1';
if(!empty($BlogId)||$_UrlIsPage||($_UrlIsBlog&&$Common_url!='')){
	/** ��ȡ��־ */
    $ShowLog=$_Logs->GetOneLog($BlogId);
	$BlogId=$ShowLog['blog_id'];
	/** ��־��ҳ��.��־��ҳ���� */
    $content_a=BreakOneLog(stripslashes($ShowLog['content']),$BlogId);
    $NewPagination=count($content_a)==2?"":$content_a['newpagenav'];
    $ShowLog['content']=$content_a[0];
    if($newpage){
	     $ShowLog['content']=$content_a[($newpage-1)];
    }
	
	/** tag�滻 */
	if($ShowLog[tag]){
		foreach($ShowLog[tag] as $value){
	        $ShowLog['content']=highlight_tag($ShowLog['content'],$value[name]);
	    }
	}
    $ShowLog['content']=$ShowLog['ismobile']==1?this_is_mobile($ShowLog['content'],'2'):$ShowLog['content'];
	//��̬��ר��
	if( $dyhb_options[allowed_make_html]=='1' ){
	     $ShowLog['content'].="\n".'<script type="text/javascript" src="count.php?blog_id='.$ShowLog['blog_id'].'"></script>';
    }
	/** ��־url����,�������۷�ҳURL */
    $url=_showlog_posturl($ShowLog);
    $url.=$dyhb_options['permalink_structure']!="default"?"?":"&";
	$the_blog_url="{$url}newpage=$newpage_c&page=$compage_c";

	/** ��һƪ,��һƪ */
   $PreLog=$_Logs->GetPreLog($BlogId);
   $NextLog=$_Logs->GetNextLog($BlogId);

   /**�������־��*/
   $RelateLog=$_Logs->GetRelateLog($dyhb_options['relate_log_num'],$ShowLog['sort_id']);

   /**�����ô��� */
   $Trackback=$_Logs->DB->gettworow("SELECT *FROM `".DB_PREFIX."trackback` where `blog_id`='$BlogId'");

   /** ��ȡ��־�������� */
   get_global_comments('blog_id',$BlogId);

   /** ����ģ�������־���� */
   include DyhbView('show.log','');
}

/**
 * ajaxǶ���������ݴ���
 * �Զ��жϴ����½
 * ubbת��
 *
 */
if($View=='addcom'){
    if($view==''){

        /** ��������Ȩ����֤ */
		if(!CheckPermission("leavemessage",$common_permission[52],'0')){
		    exit($common_width[ajax_comment][0]);
		}
		/** ��֤�� */
        $code = get_argpost('code');
		if(!CheckPermission('nospam',$common_permission[53],'0')){
        if($dyhb_options[user_code]=='1'&&!($code==$_SESSION['code']||$code==strtolower($_SESSION['code']))){
			exit($common_width[ajax_comment][1]);
		}
		}
        session_destroy();

        //��������
        $blog_id = intval(get_argpost('blog_id'));
		$photo_id = intval(get_argpost('photo_id'));
		$taotao_id = intval(get_argpost('taotao_id'));
		$mp3_id = intval(get_argpost('mp3_id'));
        $name =  html_clean(get_argpost('name'));
		$isreplymail =  intval(get_argpost('isreplymail'));
		$parentcomment_id = intval(get_argpost('parentcomment_id'));
		$hiddenmessage = get_argpost('hiddenmessage');
		if(CheckPermission("html",$common_permission[54],'0')){
           $comment = get_argpost('comment');
		}else{
		   $comment = strip_tags(get_argpost('comment'));
		}
		$email = sql_check(get_argpost('email'));
        $url = sql_check(get_argpost('url'));
		$ip=getIp();

		/** �������辭�����Ȩ����֤ **/
		$isshow=$dyhb_options[com_examine]=='1'?'0':'1';
		if(CheckPermission("minpostinterval",$common_permission[55],'0')){
		    $isshow='1';
		}

		/** ˽�� */
		if($hiddenmessage=='yes'){
		    $isshow='0';
		}

        /** ���ݴ��� */
		$username=GbkToUtf8($name,'');
		if(!ISLOGIN){
            if($result=$DB->getonerow("select *from `".DB_PREFIX."user` where `username`='$username'")){
        	    exit($common_width[ajax_comment][2]);
            }
			if($email!=''&&$result=$DB->getonerow("select *from `".DB_PREFIX."user` where `email`='$email'")){
				exit($common_width[ajax_comment][3]);
			}
		}
		require_once("base.func/html2ubb.func.php");
        $comment = trim(ubb2html($comment));

       /** ��������д�����ݿ⣬���ҷ������ݣ����ع�javascript����ʵ��ajax�������� */
       $CommentDate=array('blog_id'=>$blog_id,
		                  'file_id'=>$photo_id,
		                  'taotao_id'=>$taotao_id,
		                  'mp3_id'=>$mp3_id,
		                  'dateline'=>$localdate,
		                  'name'=>$name,
		                  'comment'=>$comment,
		                  'email'=>$email,
		                  'url'=>$url,
		                  'isshow'=>$isshow,
		                  'ip'=>$ip,
		                  'parentcomment_id'=>$parentcomment_id,
		                  'isreplymail'=>$isreplymail
	   );

       $ReturnShowComment=$_Comments->AddComments(GbkToUtf8($CommentDate,''));
		_ajax_return_back($ReturnShowComment);
        
		//���۲���ӿ�
		doHooks('showlog_comment');
        
		/** �����ʼ�֪ͨ����Ա */
		sendmail_comment($name,$email,$url,$comment);

		/** ���ۻظ�֪ͨ������ */
		$TheReturnComment=$ReturnShowComment['0'];
		if( $parentcomment_id!='0' ){
		    sendmail_reply($parentcomment_id,$TheReturnComment);
		}

	   /**������������COOKIE���ñ������ȡ,�Լ��������ۼ�ס��������Ϣ��*/
	   set_cookie('commentname',GbkToUtf8($name,''),'86400');
	   set_cookie('commenturl',GbkToUtf8($url,''),'86400');
	   set_cookie('commentemail',GbkToUtf8($email,''),'86400');

	   //���¾�̬��
	    if( $dyhb_options[allowed_make_html]=='1'){
			//������־
            if($blog_id >0){
		       //��ȡ��־����
			   $the_log=$_Logs->GetOneLog( $blog_id );
			   MakePostHtml( $the_log,'post_comment' );
			}
			//�������԰�
			if($blog_id=='0'&&$photo_id=='0'&&$taotao_id=='0'&&$mp3_id=='0'){
			   MakePagenav('guestbook','guestbook');
			}
		}


	    /** ���»����������� */
        CacheNewComment();
	    CacheNewGuestbook();
     }
     exit();
}


/**
 * ��ʾ������ģ��������
 * $_UrlIsPluginα��̬�ж��Ƿ�Ϊ���
 *
 */
if($Plugin||$_UrlIsPlugin){
	if($dyhb_options[permalink_structure]!='default'&&$dyhb_options[allowed_make_html]!='1'){
	    $Plugin=$Common_url;
	}
    if(file_exists(DOYOUHAOBABY_ROOT."/width/plugins/{$Plugin}/{$Plugin}_show.php")&&
		 $Plu=$DB->getonerow("select *from `".DB_PREFIX."plugin` where `dir`='$Plugin' and `active`='1'")){
         include_once(DOYOUHAOBABY_ROOT."/width/plugins/{$Plugin}/{$Plugin}_show.php");
	     $Plugin_func="_".$Plugin."_plugin_show";
		 //�Ƿ�Ϊ�����������������Լ�������ʾ���Ƕ����������ģ���ļ�common.php���
         if(@in_array($Plugin,unserialize($dyhb_options['plugin_self_help']))){
	           echo $Plugin_func();
         }else{
	           $_result=$Plugin_func();
	           $ModelHead=$Plu['name'];
               include DyhbView('common','');
         }
     }
}

/** ΢�������ݴ��� */
if($View=='microlog'){
    /** add taoao */
    if ($view == 'add'){
		CheckPermission("sendmicrolog",$common_permission[33],'');
		$TtContent =addslashes(get_argpost('content'));
		$TtContent=str_replace($dyhb_options[blogurl]."/width/upload","width/upload",$TtContent);//����·������,��ֹ��Ϊ������������ļ�ʧЧ
	    $TtContent=str_replace($dyhb_options[blogurl]."/file.php?id=","file.php?id=",$TtContent);//����·������,��ֹ��Ϊ������������ļ�ʧЧ
		$TtContent=str_replace($dyhb_options[blogurl]."/admin/ckeditor/plugins/smiley",'ckeditor/plugins/smiley',$TtContent);//����
		if(!CheckPermission("html","����ʹ��html���룡",'0')){
           $TtContent = strip_tags($TtContent);
		}
		if($TtContent){
		   $query = $DB->query("INSERT INTO ".DB_PREFIX."taotao (content,user_id,dateline) VALUES('$TtContent',".LOGIN_USERID.",'$localdate')");
	       CacheSideTao();
		   //����΢���ͣ�΢���͵Ĳ��������һ��
			if( $dyhb_options[allowed_make_html]=='1'){
			   MakePagenav('microlog','guestbook');
			}
		}
	    header("location:".$_SERVER['HTTP_REFERER']);
    }
    /**��del taotao��*/
    if ($view == 'del'){
		CheckPermission("cp",$common_permission[32]);
	    $TtId =intval( get_argget('id'));
	    $UserId =intval(get_argget('uid'));
	    $DB->query("DELETE FROM `".DB_PREFIX."taotao` WHERE `taotao_id`='$TtId' and `user_id` ='$UserId'");
		//�ƶ����ۣ�������תΪ���԰�����
	    $DB->query("update `".DB_PREFIX."comment` set `taotao_id`='0' where `taotao_id`='$TtId'");
	    CacheSideTao();
		//����΢���ͣ�΢���͵Ĳ��������һ��
		if( $dyhb_options[allowed_make_html]=='1'){
			 MakePagenav('microlog','guestbook');
		}
	    header("location:".$_SERVER['HTTP_REFERER']);
    }
}

/** ��ʾ�����û���Ϣ */
if($CoolId){
	$_result=_index_model_user($CoolId);
}

/** ����һЩģ�� */
switch($View){
   case "mp3": 
	   $_result=_index_model_mp3list();
   break;
   case "tag": $_result=_index_model_tag($TagCloud); break;
   case "record": $_result=_index_model_record($_sideRecord); break;
   case "link": $_result=_index_model_link(); break;
   case "user": $_result=_index_model_userlist(); break;
   case "trackback": $_result=_index_model_trackback(); break;
   case "usergroup": $_result=_index_model_usergroup(); break;
   case "search":$_result= _index_model_search_form(); break;
   case "s":
      /** ������� */
	  $_Loglist=_index_model_search();
	  if($_Loglist){
         if(($_GET[SESSION_PREFIX.'way']&&$_GET[SESSION_PREFIX.'way']=='list')||(isset($_COOKIE[SESSION_PREFIX.'way'])&&$_COOKIE[SESSION_PREFIX.'way']=='list')||($dyhb_options['default_view_list']=='1'&&get_cookie('way')==''&&$_GET['way']=='')){
	         include DyhbView('list','');
         }else{
             include DyhbView('xedni','');
         }
	  }
	  exit();
   break;
   case "guestbook":
	  $_result=_index_model_guestbook();  
      include DyhbView('common','');
   break;
   case "photo": 
	   $_result=_index_model_photo();
   break;
}

/** Modelͷ��,������ģ�嵼�� */
$ModelHead=_index_model_header();

/** ���ع���ģ�壬���$_result */
if(($View==('tag'||'link'||'search'||'record'||'photo'||'mp3'||'microlog'||'trackback'||'usergroup'||'user')||$CoolId||$Mp3Id)&&!$_POST){
     if($View!='guestbook'){
          include DyhbView('common','');
          unset($_result);
     }
}

/**
 * α��̬�����ģ��ע��
 *<!--{something here}-->
 *
 */
SmartyUrl();

?>