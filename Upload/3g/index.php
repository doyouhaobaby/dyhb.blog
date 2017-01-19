<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：index.php
        * 说明：逻辑处理，手机第一版
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

/** 核心程序 */
require_once('../width.php');
require_once('width.php');

/** 编码方式 */
header("Content-type: text/vnd.wap.wml; charset=UTF-8");

/** is mobile pagenav,希望短一些，嘿嘿 */
$IsMobilePagenav=true;

/** 博客状态检查 */
if($dyhb_options['blog_is_open']=='0'){  
	wap_header();
	wap_mes($dyhb_options['why_blog_close'],'0');
	wap_footer();
}

/** wap开启状态 */
if($dyhb_options['wap_is_open']=='0'){  
	wap_header();
	wap_mes("<font color='red'>博客wap已关闭 !</font>",'0');
	wap_footer();
}

/** 访问统计 */ 
visitor();

if($_GET[login_out]==true){
    replacesession();
    dcookies();
	$dyhb_usergroup = 0;
	$dyhb_userid = 0;
	$dyhb_username = $dyhb_password = '';
	wap_header();
	wap_mes("<font color='green'>登出成功了</font>",'');
	wap_footer();
}

/** 登录 */
if($View=='login'){
   if(!$_POST[ok]){  
	   wap_header();
       echo<<<DYHB
<div id="m">
	<form method="post" action="index.php?action=login">
		用户名<br />
	    <input type="text" name="username" /><br />
	    密码<br />
	    <input type="password" name="password" /><br />
	    <br /><input type="submit" name="ok" value=" 登 录" />
	</form>
</div>
DYHB;
       wap_footer();
   }else{
       $username = sql_check( get_argpost('username') );
       $password =md5( sql_check( get_argpost('password') ));
       if($login_userinfo=$DB->getonerow("select *from `".DB_PREFIX."user` where `username`='$username' and `password`='$password'")){
           /** 更新登陆次数、登陆时间和登陆IP */
		   $DB->query("UPDATE ".DB_PREFIX."user SET logincount=logincount+1, logintime='$localdate', loginip='$dyhb_onlineip' WHERE user_id='$login_userinfo[user_id]'");
		   $logincount = $login_userinfo['logincount']+1;
		   $dyhb_userid = $login_userinfo['user_id'];
		   $dyhb_usergroup=$login_userinfo['usergroup'];

		   /** 保存cookie */
		   set_cookie('auth',authcode("$dyhb_userid\t$password\t$logincount"),$login_life);

		   /** 更新数据库中的登陆会话 */
		   updatesession();
	       wap_header();   
           wap_mes("<font color='green'>登录成功了</font>",'index.php?action=admin');
		   wap_footer();
       }else {   
	       wap_header();   
           wap_mes("<font color='red'>用户名或密码错误！</font>",'');
		   wap_footer();
       }
   }
}

/** 用户管理中心 */
if($View=='admin'){
	if(ISLOGIN){
       wap_header();
       if(ISLOGIN){
			 echo<<<DYHB
			 你在想什么呢?
<form method="post" action="index.php?action=addtaotao" >
<input name="ttcontent"/><br><input type="submit" value="发碎语" /><br>
</form>
DYHB;
	     }
	   if(CheckPermission("cp","管理后台",'0')) echo "<a href=\"index.php?action=option\">博客设置</a><br>";
       if(CheckPermission("addentry","你不能发表日志！",'0')) echo "<a href=\"index.php?action=addlog\">发布日志</a><br>";
	   echo<<<DYHB
	   <a href="index.php?action=user&id={$dyhb_userid}">修改个人信息</a><br>
	   <a href="index.php?login_out=true">退出</a>
DYHB;
	   wap_footer();
	}else{
	   wap_header();   
       wap_mes("<font color='red'>你没有权限访问！</font>",'');
	   wap_footer();
	}
}

/** 博客设置 */
if($View=='option'){
    if(CheckPermission("cp","管理后台",'0')){
		if(!$_POST[ok]){
		wap_header();
		echo<<<DYHB
		<form method="post" action="index.php?action=option">
		名字:<br><input type="text" name="blog_title" value="{$dyhb_options[blog_title]}"/><br>
		描述:<br><input type="text" name="blog_information" value="{$dyhb_options[blog_information]}"/>
		<input type="submit" name="ok" value="修改">
		</form>
DYHB;
		 wap_footer();
		}else{
		   $blogtitle=$_POST[blog_title];
		   $description=$_POST[blog_information];
           $_Cools->UpdateOption('blog_title',GbkToUtf8($blogtitle,''));
		   $_Cools->UpdateOption('blog_information',GbkToUtf8($description,''));
		   //缓存
           CacheOptions();
           wap_header();   
           wap_mes("<font color='green'>博客设置更新成功！</font>",'index.php?action=option');
	       wap_footer();
		}
	}else{
	   wap_header();   
       wap_mes("<font color='red'>你没有权限访问！</font>",'');
	   wap_footer();
	}
}

/** 个人设置 */
$UpdUserid=$_GET['id'];
if($View=='user'&&$UpdUserid){
    if(ISLOGIN){
		if(!$_POST[ok]){
		wap_header();
		echo<<<DYHB
		Welcome $dyhb_username!<br>
		<form method="post" action="index.php?action=user&id={$UpdUserid}">
		昵称:<br><input type="text" name="coolname" value="{$dyhb_nikename}"/><br>
        主页:<br><input type="text" name="homepage" value="{$dyhb_homepage}"/>
		<input type="submit" name="ok" value="修改">
		</form>
DYHB;
		 wap_footer();
		}else{
		   $coolname=$_POST[coolname];
           $homepage=$_POST[homepage];
		   $userinfo=array(
			   'nikename'=>$coolname,
			   'homepage'=>$homepage
		   );
           $_Cools->UpdateBloggerInfo(GbkToUtf8($userinfo,''),$dyhb_userid);
           wap_header();   
           wap_mes("<font color='green'>个人信息更新成功！</font>",'index.php?action=user&id={$UpdUserid}');
	       wap_footer();
		}
	}else{
	   wap_header();   
       wap_mes("<font color='red'>你没有权限访问！</font>",'');
	   wap_footer();
	}
}

/** 用户相册 */
if($View=='photo'){
     if(CheckPermission("viewphotolist","查看主人相册！",'0')){
	 wap_header();
     $photosort_id=intval( get_args('di') );//相册分类
     $photo_id=intval( get_args('id') );//单个相片
     $PhotoSort=$_sidePhotoSorts;//相册分类
     $JustShow=get_args('show');
     //导航条消息
     if($JustShow){ $MessageHead="相册列表&nbsp;<a href=\"?action=photo\">相片列表</a>";}
	 elseif($photosort_id){ $MessageHead="相片列表&nbsp<a href=\"?action=photo\">相册列表</a>"; }
	 elseif($photo_id){ $MessageHead="<a href=\"?action=photo&show=photosort\">相册列表</a>&nbsp<a href=\"?action=photo\">最新相片</a>&nbsp;精彩相片";}
     else{ $MessageHead="最新相片&nbsp;<a href=\"?action=photo&show=photosort\">相册列表</a>";}
     echo "<div class=\"photohead\">{$MessageHead}</div>";

	 /** 相片列表 **/
      if(!$photo_id&&!$JustShow){
		 //相片分类查询
         $photo_s=$photosort_id?"and `photosort_id`='$photosort_id'":'';
         $TotalFileNum=$DB->getresultnum("SELECT count(file_id) FROM `".DB_PREFIX."file` where `name` REGEXP '(jpg|jpeg|bmp|gif|png)$' $photo_s");
         if($TotalFileNum){
              $url_c=$photosort_id?"&di=".$photosort_id:'';
              Page($TotalFileNum,$dyhb_options[every_wap_file_num],"?action=photo".$url_c);
             $AllFiles=$DB->gettworow("select *from `".DB_PREFIX."file` where `name` REGEXP '(jpg|jpeg|bmp|gif|png)$' $photo_s order by `dateline` desc limit  $pagestart,$dyhb_options[every_wap_file_num]");
         }
       $_result.="<div class='photolist'><ul>";
       if($AllFiles){
           foreach($AllFiles as $value){
			   //附件大小
               $max_file_wh=unserialize($dyhb_options['wapfilelist_width_height']);
               $now_wh=ChangeImgSize("../width/upload/".$value['path'],$max_file_wh['0'],$max_file_wh['1']);
	           $the_img_url="3gimg.php?id=$value[file_id]&w=$now_wh[w]&h=$now_wh[h]";
               $_result.=<<<DYHB
	           <li><img src="$the_img_url" border="0"/><br><a href="?action=photo&id=$value[file_id]"><span>查看大图</span></a></li>
DYHB;
           }
	   }else{
	         $_result.="该分类没有发现任何相片！";
       }
	    $_result.="</ul></div><div id=\"pagenav\">$pagination</div>";
     }

	/** 输出单个相片 */
    elseif($photo_id){
       //单张照片
       $ShowPhoto=$_Photosorts->GetIdFile($photo_id);
       //附件大小
       $max_file_wh=unserialize($dyhb_options['wapfileshow_width_height']);
       $now_wh=ChangeImgSize("../width/upload/".$ShowPhoto['path'],$max_file_wh['0'],$max_file_wh['1']);
       //上一张,下一张
       $PreFile=$_Photosorts->GetPreFile($photo_id);
       $NextFile=$_Photosorts->GetNextFile($photo_id);
       $Pre_c=$PreFile['file_id']?"&id=$PreFile[file_id]":'';
       $Next_c=$NextFile['file_id']?"&id=$NextFile[file_id]":'';
	   $the_img_url="3gimg.php?id=$ShowPhoto[file_id]&w=$now_wh[w]&h=$now_wh[h]";
       $_result.=<<<DYHB
            <div class='onephoto'><div class="prephoto"><a href="?action=photo{$Pre_c}" >上一张</a></div><a href="$the_img_url" title="$ShowPhoto[name]" target="_blank"><img src="$the_img_url"  style="border:none;"/></a><div class="nextphoto"><a href="?action=photo{$Next_c}">下一张</a></div></div>
DYHB;
    }

	/** 相册列表 */
    elseif($JustShow=='photosort'){
      //相册分类
      $PhotoSort['-1']=array('photosort_id'=>'-1','name'=>'未分类','cover'=>'','compositor'=>'0');
      $_result.="<div class='photolist'><ul>";
      if($PhotoSort){
           foreach($PhotoSort as $value){
               $Result=$_Photosorts->GetSorts($value[photosort_id]);//相册封面，没有则调用系统默认的图片
               $cover=$Result[cover]?$Result[cover]:$dyhb_options[blogurl]."/images/other/photosort.gif";
			   //封面大小
              $max_file_wh=unserialize($dyhb_options['wapfilesortlist_width_height']);
              $now_wh=ChangeImgSize($cover,$max_file_wh['0'],$max_file_wh['1']);
			  $the_img_url="3gimg.php?id=$cover&w=$now_wh[w]&h=$now_wh[h]";
               $_result.=<<<DYHB
				   <li><img src="$the_img_url"  border="0"/><br><a href="?action=photo&di=$value[photosort_id]"><span>$value[name]</span></a></li>
DYHB;
            }
	   }
       $_result.="</ul></div>";
    }
    $_result.="<p style='clear:both;'></p>";
	echo $_result;
		 wap_footer();
	}else{
	   wap_header();   
       wap_mes("<font color='red'>你没有权限访问！</font>",'');
	   wap_footer();
	} 
}

/** 日志列表数据查询 */
if($View==''){
	 //判断条件
     $tag = intval( get_argget('t') );
     $sort_id = intval( get_argget('s') );
     $record = intval( get_argget('r') );
     $user_id = intval( get_argget('u') );

   /** 通过标签查找日志*/
   if($tag){
	    $BlogIdStr=$_Tags->GetBlog_idStr($tag);
        if($BlogIdStr === false){
			wap_header();
		    wap_mes("<font color=\"red\"><b>没有日志数据</b></font>","");
		    wap_footer();
        }
        $Sql="and `blog_id` in (".$BlogIdStr.") and isshow='1' ORDER BY `istop` DESC,`dateline` DESC";
        $TotalLogNum=$DB->getresultnum("SELECT count(blog_id) FROM `".DB_PREFIX."blog` where `blog_id` in (".$BlogIdStr.") and `isshow`='1' and `ispage`='0'");
		$the_url="index.php?t=$tag";
   }
   /** 通过分类查找日志 */
   elseif($sort_id){
      $Sql="and `sort_id`='$sort_id' ORDER BY `istop` DESC,`dateline` DESC";
      $TotalLogNum=$DB->getresultnum("SELECT count(blog_id) FROM `".DB_PREFIX."blog` where `sort_id`='$sort_id' $isshow and `ispage`='0'");
	  $the_url="index.php?s=$sort_id";
  }
  /** 通过用户查找日志 */
  elseif($user_id){
  	 $Sql="and `user_id`=".$user_id ."\nORDER BY `istop` DESC,`dateline` DESC";
     $TotalLogNum=$DB->getresultnum("SELECT count(blog_id) FROM `".DB_PREFIX."blog` where `user_id`='$user_id' $isshow and `ispage`='0'");
	 $the_url="index.php?u=$user_id";
  }
  /** 通过归档查找日志 */
  elseif($record){
     $SqlTime=dyhb_date($record);
     $Sql="and `dateline` between\n" .$SqlTime[0]."\nand\n".$SqlTime[1]." $isshow order by `istop` desc,`dateline` desc";
     $TotalLogNum=$DB->getresultnum("SELECT count(blog_id) FROM `".DB_PREFIX."blog` where `dateline` between\n".$SqlTime[0]."\nand\n".$SqlTime[1]."\nand `isshow`='1' and `ispage`='0'");
	 $the_url="index.php?r=$record";
  }

  /** 首页所有日志列表 */
  else{
      $Sql="order by `istop` desc,`dateline` desc";
      $TotalLogNum=$DB->getresultnum("SELECT count(blog_id) FROM ".DB_PREFIX."blog where `ispage`='0' $isshow");
	  $the_url="index.php";
  }

  /** 按上述的条件查询数据库，返回日志列表数据 */
  if($TotalLogNum){
       Page($TotalLogNum,$dyhb_options['mobile_log_num'],$the_url);
       $AdminLogs=$_Logs->GetLog($Sql,$pagestart,$dyhb_options[mobile_log_num],$isshow='1',$ispage='0');
  }

  //wap生成
  wap_header();
  echo<<<DYHB
<div id="m">
DYHB;
  if($AdminLogs){
        foreach($AdminLogs as $value){
			 $dateline=date("Y-m-d H:i",$value[dateline]);
			 $wap_p_title=$value[ismobile]=='1'?this_is_mobile($value[title],'1'):$value[title];
			 echo<<<DYHB
<div class="title"><a href='index.php?s=$value[sort_id]'>[$value[name]]</a><a href="index.php?action=show&id={$value[blog_id]}">{$wap_p_title}</a></div>
<div class="info">{$dateline}</div>
<div class="info2">
评论:$value[commentnum] 阅读:$value[viewnum] 
DYHB;
if( ISLOGIN ){
echo<<<DYHB
<a href="index.php?action=longlog&id=$value[blog_id]">续写</a>
<a href="index.php?action=dellog&id=$value[blog_id]">删除</a>
DYHB;
}
echo<<<DYHB
</div>
DYHB;
      }
}
echo<<<DYHB
<div id="page">$pagination</div>
</div>
DYHB;
  wap_footer();
}

/** 文章显示页面 */
if($View=='show'){
	$newpage = intval( get_argget('newpage') );
	$blog_id = intval( get_argget('id') );
if($blog_id){
	$IsMobile=true;//这里确定是否为手机版本,标签和分页都要用到
    /** 获取日志 */
    $ShowLog=$_Logs->GetOneLog($blog_id);
	$blog_id=$ShowLog['blog_id'];
	/** 增加点击 */
	$_Logs->AddView($blog_id);

    /** 日志分页条.日志分页处理 */
	 $content_a=BreakOneLog();
     $NewPagination=count($content_a)==2?"":$content_a['newpagenav'];
	 $ShowLog['content']=$content_a[0];
	 if($newpage){
	      $ShowLog['content']=$content_a[($newpage-1)];
	 }

	/** 上一篇,下一篇 */
   $PreLog=$_Logs->GetPreLog($blog_id);
   $NextLog=$_Logs->GetNextLog($blog_id);
    $dateline=date('Y-m-d H:i',$ShowLog['dateline']);
	//$content=html2text($ShowLog['content']);
	$content=strip_tags($ShowLog['content']);
	$content=$ShowLog[ismobile]=='1'?this_is_mobile($ShowLog[content],'2'):$ShowLog[content];
	$title=$ShowLog[ismobile]=='1'?this_is_mobile($ShowLog[title],'1'):$ShowLog[title];

	/** 开始 */
	wap_header();
	if($PreLog){
	   $prelog_url="<a href=\"index.php?action=show&id=$PreLog[0]\">$PreLog[1]</a>";
	}else{
	   $prelog_url="没有了";
	}
	if($NextLog){
	   $nextlog_url="<a href=\"index.php?action=show&id=$NextLog[0]\">$NextLog[1]</a>";
	}else{
	   $nextlog_url="没有了";
	}
	$tag=$ShowLog[tags]?"<div class=\"tags\">Tag:$ShowLog[tags]</div>":'';
	echo<<<DYHB
<div id="m">
	<div class="posttitle">$title</div>
	<div class="postinfo">post by:<a href='index.php?u=$ShowLog[user_id]'>$ShowLog[user]</a><br>分类:<a href="index.php?s=$ShowLog[sort_id]">$ShowLog[name]</a><br>Time:$dateline<br><div class="tags">$tag</div>
DYHB;
	if(ISLOGIN) {
	echo<<<DYHB
	操作:(<a href="index.php?action=longlog&id=$blog_id">续写</a>.<a href="index.php?action=dellog&id=$blog_id">删除</a>)<br>
DYHB;
	}
	echo<<<DYHB
	</div>
	<div class="postcont">$content</div>
	<div id="page">$NewPagination</div>
	<div class="t">查看评论:<a href='index.php?action=com&id=$blog_id'>($ShowLog[commentnum])</a></div>
	<div class="prelog">上一篇:$prelog_url</div>
	<div class="nextlog">下一篇:$nextlog_url</div>
</div>
DYHB;
	wap_footer();
}
}

/** 评论单独显示页面，降低日志页面数据量请求 */
if($View=='com'){   
	$blog_id = intval( get_argget('id') );
	if($blog_id){
	/** 手机版本不嵌套评论 */
   $TotalComment=$DB->getresultnum("SELECT count(comment_id) FROM `".DB_PREFIX."comment` where `blog_id`='$blog_id' and `isshow`='1'");
   if($TotalComment){
        Page($TotalComment,$dyhb_options['mobile_comment_num']);
	    $ShowComment=$_Comments->GetComment("order by `comment_id` desc",$blog_id,$isshow=1,$pagestart,$dyhb_options['mobile_comment_num'],'0','0','0');
   }
	 wap_header();
	 echo<<<DYHB
     <p><a href="index.php?action=show&id=$blog_id">返回日志</a></p>
DYHB;
	 echo<<<DYHB
<div class="c">
DYHB;
		if ($ShowComment ){
			 foreach($ShowComment as $key=>$value){
			 $dateline=date('Y-m-d H:i',$value[dateline]);
			 $name=$value[url]?"<a href=\"$value[url]\">$value[name]</a>":$value[name];
			 $comment=$value[ismobile]=='1'?this_is_mobile(strip_tags($value[comment]),'2'):strip_tags($value[comment]);
		echo<<<DYHB
		<div class="l">
		<b>{$name}</b>
		<div class="info">$dateline</div>
		<div class="comcont">$comment</div>
		</div>
DYHB;
			}
		}
echo<<<DYHB
</div>
<div id="page">$pagination</div>
DYHB;
	 echo<<<DYHB
<div class="t">发表评论：</div>
	<div class="c">
		<form method="post" action="index.php?action=addcom">
		<input name="blog_id"  type="hidden" value="{$blog_id}" />
DYHB;
	    if(ISLOGIN){
			echo<<<DYHB
		欢迎你，$dyhb_username<input type="hidden" name="name" value="$dyhb_username"/>
	    <input type="hidden" name="email" value="$dyhb_email"/>
		<input type="hidden" name="url" value="$dyhb_homepage"/><br />
DYHB;
		}
		else{
			echo<<<DYHB
		昵称<br /><input type="text" name="name"/><br />
DYHB;
		}
echo<<<DYHB
	    <input type="text" name="comment" value=""/><br />
		<input type="submit" value="发表评论" />
		</form>
</div>
DYHB;
	 wap_footer();
	}
}

//日志操作
function cacheaction(){
 global $dyhb_options;
 //更新缓存
cachenewlog();
cachehotlog();
cacheyearhotlog();
cachemouthhotlog();
CacheFlashLog();
}

/** 续写日志，不支持编辑日志 */
if($View=='longlog'){
	if(!CheckPermission("editentry","你不能编辑日志！",'0')){
	     wap_header();
         wap_mes("<font color='red'>你没有权限加长日志</font>",'');
         wap_footer();
	}
    $blog_id = intval ( $_GET[id] );
    $UpdLog=$_Logs->GetOneLog ( $blog_id );
	if($dyhb_userid==$UpdLog[user_id]){
		if(!CheckPermission("editotherentry","你不能编辑别人的日志！",'')){
	     wap_header();
         wap_mes("<font color='red'>你没有权限编辑别人的日志</font>",'');
         wap_footer();
	  }
	}
}

/** 添加日志 */
if($View=="addlog"||$View=="longlog"){
  if(!CheckPermission("addentry","你不能发表日志！",'')){
     wap_header();
         wap_mes("<font color='red'>你没有权限发布的日志</font>",'');
         wap_footer();
  }
   wap_header();
   echo<<<DYHB
<div id="m">
<form action="index.php?action=savelog" method="post">
标题：<br /><input type="text" name="title" value="$UpdLog[title]" /><br />
分类：<br />
	      <select name="sort_id" id="sort">
DYHB;
			$_sideSorts[] = array('sort_id'=>-1, 'name'=>'选择分类...');
			foreach($_sideSorts as $val){
			$flg = $val['sort_id'] == $UpdLog[sort_id] ? 'selected' : '';
		    echo<<<DYHB
			<option value="$val[sort_id]" $flg>$val[name]</option>
DYHB;
			}
echo<<<DYHB
	      </select>
<br />
内容：<br /><input type="text" name="content"/><br />
<input type="hidden" name="old_content" value="$UpdLog[content]"/>
<input type="hidden" name="blog_id" value="$UpdLog[blog_id]"/>
<input type="submit" value="发布日志" />
</form>
</div>  
DYHB;
	wap_footer();
}

/** 保存日志数据 */
if($View=='savelog'){
     $title =  get_argpost('title') ;
	 $blog_id = intval( get_argpost('blog_id') );
     $sort_id = intval ( get_argpost('sort_id') );
	 $old_content =  get_argpost('old_content') ;
     $content = get_argpost('content') ;

     if($title==''||$content==''){
	    wap_header();
		if($blog_id>0){
		   $the_url="index.php?action=longlog&id=$blog_id";
		}else{
		   $the_url="index.php?action=addlog";
		}
	    wap_mes("<font color='red'>标题或者内容不能为空</font>",$the_url);
	    wap_footer();
	 }

     //$blog_id>0,续写日志
	 if($blog_id>0){
	     $content=$old_content.$content;
	 }
     $SaveLogDate=array(
	              'title'=>$title,
	              'sort_id'=>$sort_id,
	              'content'=>$content,
	              'user_id'=>$dyhb_userid,
		          'dateline'=>$localdate,
		          'ismobile'=>'1'
    );
	
	//保存数据
	if($blog_id>0){
		$_Logs->UpdateLog(GbkToUtf8($SaveLogDate,''),$blog_id);
	}else{
	    $blog_id=$_Logs->AddLog(GbkToUtf8($SaveLogDate,''));
	}
	//更新缓存
cacheaction();
if($dyhb_options['cache_cms_log']=='1'){
  CacheTag();
  CacheCmsNew();
  CacheCmsSort();
}
	wap_header();
	wap_mes("<font color='green'>发布日志成功了</font>","index.php?action=show&id=$blog_id");
	wap_footer();
}

/** 删除日志 */
if($View=='dellog'){
	if(!CheckPermission("cp","删除日志！",'0')){
	     wap_header();
         wap_mes("你没有权限删除日志</font>",'');
         wap_footer();
	 }
    $blog_id = intval ( $_GET[id] );
    $UpdLog=$_Logs->DeleteLog ( $blog_id );
	//更新缓存
cacheaction();
if($dyhb_options['cache_cms_log']=='1'){
  CacheTag();
  CacheCmsNew();
  CacheCmsSort();
}
	wap_header();
	wap_mes("<font color='green'>删除日志成功了</font>","index.php");
	wap_footer();
}

/** 评论列表 */
if($View=='comment'){
	if($dyhb_usergroup=='1'){
       $isshow_c='';
	}else{
	   $isshow_c='where isshow=1';
	}
    $TotalComment=$DB->getresultnum("SELECT count(comment_id) FROM `".DB_PREFIX."comment` $isshow_c");
   if($TotalComment){
        Page($TotalComment,$dyhb_options['mobile_comment_num']);
	    $ShowComment=$DB->gettworow("select *from ".DB_PREFIX."comment $isshow_c order by comment_id desc,dateline desc limit $pagestart, $dyhb_options[mobile_comment_num]");
   }
   wap_header();
   echo<<<DYHB
<div id="m">
DYHB;
if($ShowComment){
foreach($ShowComment as $value){
	 $YouCan=false;
	 if(CheckPermission("cp","管理评论权限！",'0')){
	    $YouCan=true;
	 }
	$ishide = $YouCan === true && $value['isshow']=='0'?'<font color="red" size="1">[待审]</font>':'';
	if($value[file_id]!='0'){
	   $torelation="<font color='green'>相册评论</font>";
	}elseif($value[taotao_id]!='0'){
	   $torelation="<font color='blue'>心情评论</font>";
	}elseif($value[mp3_id]!='0'){
	   $torelation="<font color='yellow'>音乐评论</font>";
	}elseif($value[blog_id]!='0'){
	   $torelation="<a href='index.php?action=show&id=$value[blog_id]'><font color='purple'>日志评论</font></a>";
	}else{
	   $torelation="<font color='#44d9f9'>留言内容</font>";
	}
    $dateline=date('Y-m-d H:i',$value[dateline]);
	$comment=$value[ismobile]=='1'?this_is_mobile(strip_tags($value[comment]),'2'):strip_tags($value[comment]);
	$mobile_title=$value[ismobile]=='1'?this_is_mobile('','1'):'';
    echo<<<DYHB
<div class="comcont">{$mobile_title}{$comment}$ishide
DYHB;
if(ISLOGIN){
	echo<<<DYHB
<a href="index.php?action=delcom&id=$value[comment_id]"><font size="1">[删除]</font></a>
DYHB;
}
echo<<<DYHB
</div>
<div class="info">评论属性：$torelation</div>
<div class="cominfo">
DYHB;
if($YouCan){
	echo<<<DYHB
<a href="index.php?action=hidecom&id=$value[comment_id]">屏蔽</a>
<a href="index.php?action=showcom&id=$value[comment_id]">审核</a>
<br />
DYHB;
}
echo<<<DYHB
$dateline by:$value[name]
</div>
DYHB;
}}
echo<<<DYHB
<div id="page">$pagination</div>
</div>
DYHB;
wap_footer();
}

/** 添加评论 */
if($View=='addcom'){
     if(!CheckPermission("leavemessage","添加评论！",'0')){
	     wap_header();
         wap_mes("<font color='red'>你没有权限添加评论</font>",'');
         wap_footer();
	 }
	 //评论数据
	 $blog_id = intval ( get_argpost('blog_id') );
	 $name = sql_check( get_argpost('name') );
	 $comment = strip_tags(sql_check( get_argpost('comment') ));
	 $url = sql_check( get_argpost('url') );
	 $email = sql_check( get_argpost('email') );
	 $ip=getIp();
	 if($name==''||$comment==''){
		 wap_header();
		 wap_mes("<font color=\"red\"><b>名字或内容不能为空。</b></font>","");
		 wap_footer();
	 }
	 //评论数据
     $CommentDate=array('blog_id'=>$blog_id,
		                'dateline'=>$localdate,
		                'name'=>$name,
		                'comment'=>$comment,
		                'url'=>$url,
		                'email'=>$email,
		                'ip'=>$ip,
		                'ismobile'=>'1'
	 );
	 $_Comments->AddComments(GbkToUtf8($CommentDate,''));
	 CacheNewComment();
	 wap_header();
	 wap_mes("<font color='green'>发布评论成功了</font>>",'index.php?action=com&id='.$blog_id);
	 wap_footer();
 }


//删除评论
if($View=='delcom'){
   if(!CheckPermission("leavemessage","添加评论！",'0')){
	     wap_header();
         wap_mes("你没有权限添加评论",'');
         wap_footer();
	 }
	 $Comment_id=intval( $_GET[id] );
  $_Comments->DeleteComment($Comment_id );
  CacheNewComment();
	CacheNewGuestbook();
  wap_header();
  wap_mes("<font color='green'>删除评论成功了</font>",'index.php?action=comment');
  wap_footer();
}

//屏蔽评论
if($View=='hidecom'){
   if(!CheckPermission("leavemessage","添加评论！",'0')){
	     wap_header();
         wap_mes("你没有权限添加评论",'index.php?action=comment');
         wap_footer();
	 }
	 $Comment_id=intval( $_GET[id] );
	 $_Comments->HideShowComment('0',$Comment_id);
	 CacheNewComment();
	CacheNewGuestbook();
	 wap_header();
         wap_mes("<font color='green'>屏蔽评论成功了</font>",'index.php?action=comment');
         wap_footer();
}

//审核评论
if($View=='showcom'){
   if(!CheckPermission("leavemessage","添加评论！",'0')){
	     wap_header();
         wap_mes("你没有权限添加评论</div>",'');
         wap_footer();
	 }
	 $Comment_id=intval( $_GET[id] );
	 $_Comments->HideShowComment('1',$Comment_id );
	 CacheNewComment();
	CacheNewGuestbook();
	 wap_header();
         wap_mes("<font color='green'>审核评论成功了</font>",'index.php?action=comment');
         wap_footer();
}

 /** 分类 */
 if($View=='sort'){   
	  wap_header();
	  echo "<ul>";
      if($_sideSorts){
	      foreach($_sideSorts as $value)
              echo "<li><a href='index.php?s=$value[sort_id]'>$value[name]</a><span>($value[lognum])</span></li>";
	      }
      echo "</ul>";
	  wap_footer();
 }
 
 
/** record */
if($View=='record'){   
	 wap_header();
	 echo "<ul>";
     if($_sideRecord){
	      foreach($_sideRecord as $value)
               echo "<li><a href=\"index.php?r=$value[record_id]\">$value[record]</a><span>($value[lognum])</span></li>";
	      }
     echo "</ul>";
	 wap_footer();
}

 /** 标签 */
 if($View=='tag'){   
	 $TotalTagNum=$DB->getresultnum("SELECT count(tag_id) FROM `".DB_PREFIX."tag`");
	 if($TotalTagNum){
	      Page($TotalTagNum,$dyhb_options[mobile_tag_num]);
	      $Tags=TagAction($_Tags->GetTag('',$pagestart,$dyhb_options[mobile_tag_num]));
	 }
     wap_header();
	 echo "<ul>";
	 if($Tags){
	     foreach($Tags as $value){
             echo "<li><a href='index.php?t=$value[tag_id]'>$value[name]</a><span>($value[lognum])</span></li>";
	     }
	 }
     echo "</ul><div id=\"page\">$pagination</div>";
	 wap_footer();
 }

 /** 滔滔心情 */
 if($View=='taotao'){
      $totalTaotaonum=$DB->getresultnum("SELECT count(taotao_id) FROM `".DB_PREFIX."taotao`");
      if($totalTaotaonum){    
          Page($totalTaotaonum,$dyhb_options['mobile_taotao_num']);
          $TaotaoList=$DB->gettworow("select *from `".DB_PREFIX."taotao` order by `dateline` desc limit $pagestart,$dyhb_options[mobile_taotao_num]"); 
	  }
	     wap_header();
	     if($TaotaoList){
	          foreach($TaotaoList as $value){
				  $b=$_Cools->GetBloggerInfo($value['user_id']);
				  $dateline=ChangeDate($value['dateline'],'Y-m-d H:i');
				  $name=$b[nikename]?$b[nikename]:$b[username];
				  $name=$value[ismobile]=='1'?this_is_mobile($name,'1'):$name;
                  $value[content]=$value[ismobile]=='1'?this_is_mobile(strip_tags(stripslashes($value[content])),'2'):strip_tags(stripslashes($value[content]));
				  $del=ISLOGIN===true?"<a href=\"index.php?action=deltaotao&id=$value[taotao_id]&uid=$value[user_id]\">删除</a>":'';
				  echo<<<DYHB
<div class="twcont">[$name]:$value[content]</a></div>
<div class="twinfo">$dateline
$del
</div>
DYHB;
		    }
		}
echo<<<DYHB
<div id="page">$pagination</div>
DYHB;
	   wap_footer();
}

   
//添加心情
if(ISLOGIN&&$View=='addtaotao'){
	     $TtContent = GbkToUtf8(sql_check( get_argpost('ttcontent') ),'');
	     if (!empty($TtContent)){
		       $query = $DB->query("INSERT INTO `".DB_PREFIX."taotao` (content,user_id,dateline,ismobile) VALUES('$TtContent','".$dyhb_userid."','$localdate','1')");
	     }
	    CacheSideTao();
	    wap_header();
        wap_mes("<font color='green'>发布心情成功了</font>",'index.php?action=admin');
        wap_footer();
}

//删除心情
if($View=='deltaotao'){
	 if(!CheckPermission("cp","删除心情！",'0')){
	     wap_header();
         wap_mes("你没有权限删除",'index.php?action=taotao');
         wap_footer();
	 }
     $taotao_id= intval( get_argget('id') );
     $UserId =  intval( get_argget('uid') );
     $DB->query("DELETE FROM `".DB_PREFIX."taotao` WHERE `taotao_id`=$taotao_id and `user_id` ='$UserId'");
     CacheSideTao();
     wap_header();
     wap_mes("<font color='green'>删除心情成功了</font>",'index.php?action=taotao');
     wap_footer();
}

?>