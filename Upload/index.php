<?php
/**================[^_^]================**\
      ---- 因为有梦，所以有目标 ----
@----------------------------------------@
        * 文件：index.php
        * 说明：前台主程序
        * 作者：小牛哥
        * 时间：2010-05-06 20:22
        * 版本：DoYouHaoBaby-blog 概念版
        * 主页：www.doyouhaobaby.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

/** 加载核心部件 */
require_once('common.php');
/** 单一入口 url传值 */
$BlogId = intval( get_argget('p') );
$CoolId = intval( get_argget('c') );
$Plugin = sql_check(CheckSql( get_args('plugin') ) );

/** 评论记住信息之cookie */
$_Comment_name=get_cookie('commentname');
$_Comment_url=get_cookie('commenturl');
$_Comment_email=get_cookie('commentemail');


/**
 * 查询日志列表数据
 * 按分类，标签，归档
 * $_UrlIs_XX用于伪静态判断，到底是那种数据
 * $Common_url 如果启用伪静态的话，那么把伪静态URL分析数据返回回来查询数据库
 *
 */
if($View == ''&&!$BlogId&&!$Mp3Id&&!$CoolId&&!$Plugin&&!$ParentSortId&&!$_UrlIsPage&&!$_UrlIsPlugin&&!$_UrlIsPagenav&&!$_UrlIsBlog){
	/** 初始化变量 */
    $record = intval( get_args('r') );
    $tag = sql_check(CheckSql( get_args('t') )) ;
    $sortid = intval( get_args('s') );
    $userid = intval( get_args('u') ) ;
    $theurl='';
    /** 隐藏日志权限设置 */
    if(CheckPermission("seehiddenentry",$common_permission[51],'0')&&!$isAdmin){
     	$isshow="";
     }else{
     	$isshow="and `isshow`='1'" ;
     }

    /** 通过标签查找日志*/
    if($tag||$_UrlIsTag){
    	if($dyhb_options['permalink_structure']!='default'&&$_UrlIsTag){
    		$tag=$Common_url;
    	}
	    $BlogIdStr=$_Tags->GetBlog_idStrByName($tag);
        if($BlogIdStr === false){
	        page_not_found();
        }
		//标签信息，用于表签查找的数据的导航条
        $tag_infor=$_Tags->GetOneTagByName($tag);

        $Sql="and `blog_id` in (".$BlogIdStr.") $isshow ORDER BY `istop` DESC,`dateline` DESC";
        $TotalLogNum=$DB->getresultnum("SELECT count(blog_id) FROM `".DB_PREFIX."blog` where `blog_id` in (".$BlogIdStr.") and `isshow`='1' and `ispage`='0'");
		$theurl=get_rewrite_tag($tag_infor);
   }
   /** 通过分类查找日志 */
   elseif($sortid||$_UrlIsCategory){
	  if($dyhb_options['permalink_structure']!='default'&&$_UrlIsCategory){
		 //如果开启伪静态，而且值为default，那么这个分类为未分类，日志的sort_id为-1
	 	 if($Common_url!='default'){
	 	    $thesort=$DB->getonerow("select *from `".DB_PREFIX."sort` where `name`='$Common_url' OR `urlname`='$Common_url'");
		     $sortid=$thesort['sort_id'];
	 	 }else{
	 	 	 $sortid='-1';
	 	 }
	 }

	 /**
	  * 查询程序的父分类和子分类，父分类用于伪静态多级分类的导航条和URL，像这样子，我的故事/小学故事/哥只是个传说 。
	  * 父分类是一个三维数组，键值sort必须存在，其值为一个二位数组
	  */
	 if($sortid!='-1'&&$sortid!=''){
		 //当前分类，父亲分类，子分类
         $ListSort=$_Sorts->GetIdSort($sortid);
	     $Loglist_parsort=$_Sorts->GetThreePar($ListSort);//父分类
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
  /** 通过用户查找日志 */
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
	 /** $sort_userinfo 导航条用户信息 */
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
  /** 通过归档查找日志 */
  elseif($record||$_UrlIsRecord){
  	 if($dyhb_options['permalink_structure']!='default'&&$_UrlIsRecord){
  	 	 $record=$Common_url;
  	 }
     /** 日志归档，record　*/
	 $the_year=substr($record,'0','4');
	 $the_mouth=substr($record,'4','2');

     $SqlTime=dyhb_date($record);
     $Sql="and `dateline` between\n" .$SqlTime[0]."\nand\n".$SqlTime[1]." $isshow order by `istop` desc,`dateline` desc";
     $TotalLogNum=$DB->getresultnum("SELECT count(blog_id) FROM `".DB_PREFIX."blog` where `dateline` between\n".$SqlTime[0]."\nand\n".$SqlTime[1]."\nand `isshow`='1' and `ispage`='0'");
	 //没有中文
	 //$theurl=get_rewrite_record($the_year,$the_mouth);
  }

  /** 首页所有日志列表 */
  elseif(!$record&&!$userid&&!$sortid&&!$tag&&!$_UrlIsCategory&&!$_UrlIsRecord&&!$_UrlIsTag&&!$_UrlIsAuthor){
      $Sql="order by `istop` desc,`dateline` desc";
      $TotalLogNum=$DB->getresultnum("SELECT count(blog_id) FROM ".DB_PREFIX."blog where `ispage`='0' $isshow");
      $theurl="";

  }

  /** 按上述的条件查询数据库，返回日志列表数据 */
  if($TotalLogNum){
       Page($TotalLogNum,$dyhb_options['user_log_num'],$theurl);
       $_Loglist=$_Logs->GetLog($Sql,$pagestart,$dyhb_options['user_log_num'],$show='1',$ispage='0');
  }

  /** 加载模板文件，输出日志列表数据，这里加入了默认模板是否按列表显示 */
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
 * 输出日志(输出单个日志)
 * $newpage_c日志分页，用于日志分页函数使用
 * $page 评论分页参数
 */
$newpage = intval( get_args('newpage') );
$newpage_c=$newpage?$newpage:'1';
$compage_c=$page?$page:'1';
if(!empty($BlogId)||$_UrlIsPage||($_UrlIsBlog&&$Common_url!='')){
	/** 获取日志 */
    $ShowLog=$_Logs->GetOneLog($BlogId);
	$BlogId=$ShowLog['blog_id'];
	/** 日志分页条.日志分页处理 */
    $content_a=BreakOneLog(stripslashes($ShowLog['content']),$BlogId);
    $NewPagination=count($content_a)==2?"":$content_a['newpagenav'];
    $ShowLog['content']=$content_a[0];
    if($newpage){
	     $ShowLog['content']=$content_a[($newpage-1)];
    }
	
	/** tag替换 */
	if($ShowLog[tag]){
		foreach($ShowLog[tag] as $value){
	        $ShowLog['content']=highlight_tag($ShowLog['content'],$value[name]);
	    }
	}
    $ShowLog['content']=$ShowLog['ismobile']==1?this_is_mobile($ShowLog['content'],'2'):$ShowLog['content'];
	//静态化专用
	if( $dyhb_options[allowed_make_html]=='1' ){
	     $ShowLog['content'].="\n".'<script type="text/javascript" src="count.php?blog_id='.$ShowLog['blog_id'].'"></script>';
    }
	/** 日志url分析,用于评论分页URL */
    $url=_showlog_posturl($ShowLog);
    $url.=$dyhb_options['permalink_structure']!="default"?"?":"&";
	$the_blog_url="{$url}newpage=$newpage_c&page=$compage_c";

	/** 上一篇,下一篇 */
   $PreLog=$_Logs->GetPreLog($BlogId);
   $NextLog=$_Logs->GetNextLog($BlogId);

   /**　相关日志　*/
   $RelateLog=$_Logs->GetRelateLog($dyhb_options['relate_log_num'],$ShowLog['sort_id']);

   /**　引用处理 */
   $Trackback=$_Logs->DB->gettworow("SELECT *FROM `".DB_PREFIX."trackback` where `blog_id`='$BlogId'");

   /** 获取日志留言内容 */
   get_global_comments('blog_id',$BlogId);

   /** 调用模板输出日志数据 */
   include DyhbView('show.log','');
}

/**
 * ajax嵌套评论数据处理
 * 自动判断处理登陆
 * ubb转换
 *
 */
if($View=='addcom'){
    if($view==''){

        /** 发表留言权限验证 */
		if(!CheckPermission("leavemessage",$common_permission[52],'0')){
		    exit($common_width[ajax_comment][0]);
		}
		/** 验证码 */
        $code = get_argpost('code');
		if(!CheckPermission('nospam',$common_permission[53],'0')){
        if($dyhb_options[user_code]=='1'&&!($code==$_SESSION['code']||$code==strtolower($_SESSION['code']))){
			exit($common_width[ajax_comment][1]);
		}
		}
        session_destroy();

        //评论数据
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

		/** 发言无需经过审核权限验证 **/
		$isshow=$dyhb_options[com_examine]=='1'?'0':'1';
		if(CheckPermission("minpostinterval",$common_permission[55],'0')){
		    $isshow='1';
		}

		/** 私语 */
		if($hiddenmessage=='yes'){
		    $isshow='0';
		}

        /** 数据处理 */
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

       /** 评论数据写入数据库，并且返回数据，返回供javascript处理，实现ajax返回数据 */
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
        
		//评论插件接口
		doHooks('showlog_comment');
        
		/** 评论邮件通知管理员 */
		sendmail_comment($name,$email,$url,$comment);

		/** 评论回复通知评论者 */
		$TheReturnComment=$ReturnShowComment['0'];
		if( $parentcomment_id!='0' ){
		    sendmail_reply($parentcomment_id,$TheReturnComment);
		}

	   /**　发送评论人COOKIE，好被公告获取,以及用于评论记住评论人信息　*/
	   set_cookie('commentname',GbkToUtf8($name,''),'86400');
	   set_cookie('commenturl',GbkToUtf8($url,''),'86400');
	   set_cookie('commentemail',GbkToUtf8($email,''),'86400');

	   //更新静态化
	    if( $dyhb_options[allowed_make_html]=='1'){
			//更新日志
            if($blog_id >0){
		       //获取日志数据
			   $the_log=$_Logs->GetOneLog( $blog_id );
			   MakePostHtml( $the_log,'post_comment' );
			}
			//更新留言板
			if($blog_id=='0'&&$photo_id=='0'&&$taotao_id=='0'&&$mp3_id=='0'){
			   MakePagenav('guestbook','guestbook');
			}
		}


	    /** 更新缓存评论数据 */
        CacheNewComment();
	    CacheNewGuestbook();
     }
     exit();
}


/**
 * 显示插件信心，插件处理
 * $_UrlIsPlugin伪静态判断是否为插件
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
		 //是否为独立插件，独立插件自己单独显示，非独立插件调用模板文件common.php输出
         if(@in_array($Plugin,unserialize($dyhb_options['plugin_self_help']))){
	           echo $Plugin_func();
         }else{
	           $_result=$Plugin_func();
	           $ModelHead=$Plu['name'];
               include DyhbView('common','');
         }
     }
}

/** 微博客数据处理 */
if($View=='microlog'){
    /** add taoao */
    if ($view == 'add'){
		CheckPermission("sendmicrolog",$common_permission[33],'');
		$TtContent =addslashes(get_argpost('content'));
		$TtContent=str_replace($dyhb_options[blogurl]."/width/upload","width/upload",$TtContent);//附件路径处理,防止因为更换域名造成文件失效
	    $TtContent=str_replace($dyhb_options[blogurl]."/file.php?id=","file.php?id=",$TtContent);//附件路径处理,防止因为更换域名造成文件失效
		$TtContent=str_replace($dyhb_options[blogurl]."/admin/ckeditor/plugins/smiley",'ckeditor/plugins/smiley',$TtContent);//表情
		if(!CheckPermission("html","允许使用html代码！",'0')){
           $TtContent = strip_tags($TtContent);
		}
		if($TtContent){
		   $query = $DB->query("INSERT INTO ".DB_PREFIX."taotao (content,user_id,dateline) VALUES('$TtContent',".LOGIN_USERID.",'$localdate')");
	       CacheSideTao();
		   //更新微博客，微博客的参数和这个一样
			if( $dyhb_options[allowed_make_html]=='1'){
			   MakePagenav('microlog','guestbook');
			}
		}
	    header("location:".$_SERVER['HTTP_REFERER']);
    }
    /**　del taotao　*/
    if ($view == 'del'){
		CheckPermission("cp",$common_permission[32]);
	    $TtId =intval( get_argget('id'));
	    $UserId =intval(get_argget('uid'));
	    $DB->query("DELETE FROM `".DB_PREFIX."taotao` WHERE `taotao_id`='$TtId' and `user_id` ='$UserId'");
		//移动评论，将评论转为留言板内容
	    $DB->query("update `".DB_PREFIX."comment` set `taotao_id`='0' where `taotao_id`='$TtId'");
	    CacheSideTao();
		//更新微博客，微博客的参数和这个一样
		if( $dyhb_options[allowed_make_html]=='1'){
			 MakePagenav('microlog','guestbook');
		}
	    header("location:".$_SERVER['HTTP_REFERER']);
    }
}

/** 显示个人用户信息 */
if($CoolId){
	$_result=_index_model_user($CoolId);
}

/** 其它一些模块 */
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
      /** 搜索结果 */
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

/** Model头部,导航条模板导航 */
$ModelHead=_index_model_header();

/** 加载公共模板，输出$_result */
if(($View==('tag'||'link'||'search'||'record'||'photo'||'mp3'||'microlog'||'trackback'||'usergroup'||'user')||$CoolId||$Mp3Id)&&!$_POST){
     if($View!='guestbook'){
          include DyhbView('common','');
          unset($_result);
     }
}

/**
 * 伪静态和清除模板注释
 *<!--{something here}-->
 *
 */
SmartyUrl();

?>