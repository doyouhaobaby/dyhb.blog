<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * �ļ���index.php
        * ˵�����߼������ֻ���һ��
        * ���ߣ�Сţ��
        * ʱ�䣺2010-07-22 0:17
        * �汾��DoYouHaoBaby-blog �����.����˿�� (�ر��)
        * ��ҳ��www.doyouhaobaby.com
		* ��̳��bbs.56swun.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

/** ���ĳ��� */
require_once('../width.php');
require_once('width.php');

/** ���뷽ʽ */
header("Content-type: text/vnd.wap.wml; charset=UTF-8");

/** is mobile pagenav,ϣ����һЩ���ٺ� */
$IsMobilePagenav=true;

/** ����״̬��� */
if($dyhb_options['blog_is_open']=='0'){  
	wap_header();
	wap_mes($dyhb_options['why_blog_close'],'0');
	wap_footer();
}

/** wap����״̬ */
if($dyhb_options['wap_is_open']=='0'){  
	wap_header();
	wap_mes("<font color='red'>����wap�ѹر� !</font>",'0');
	wap_footer();
}

/** ����ͳ�� */ 
visitor();

if($_GET[login_out]==true){
    replacesession();
    dcookies();
	$dyhb_usergroup = 0;
	$dyhb_userid = 0;
	$dyhb_username = $dyhb_password = '';
	wap_header();
	wap_mes("<font color='green'>�ǳ��ɹ���</font>",'');
	wap_footer();
}

/** ��¼ */
if($View=='login'){
   if(!$_POST[ok]){  
	   wap_header();
       echo<<<DYHB
<div id="m">
	<form method="post" action="index.php?action=login">
		�û���<br />
	    <input type="text" name="username" /><br />
	    ����<br />
	    <input type="password" name="password" /><br />
	    <br /><input type="submit" name="ok" value=" �� ¼" />
	</form>
</div>
DYHB;
       wap_footer();
   }else{
       $username = sql_check( get_argpost('username') );
       $password =md5( sql_check( get_argpost('password') ));
       if($login_userinfo=$DB->getonerow("select *from `".DB_PREFIX."user` where `username`='$username' and `password`='$password'")){
           /** ���µ�½��������½ʱ��͵�½IP */
		   $DB->query("UPDATE ".DB_PREFIX."user SET logincount=logincount+1, logintime='$localdate', loginip='$dyhb_onlineip' WHERE user_id='$login_userinfo[user_id]'");
		   $logincount = $login_userinfo['logincount']+1;
		   $dyhb_userid = $login_userinfo['user_id'];
		   $dyhb_usergroup=$login_userinfo['usergroup'];

		   /** ����cookie */
		   set_cookie('auth',authcode("$dyhb_userid\t$password\t$logincount"),$login_life);

		   /** �������ݿ��еĵ�½�Ự */
		   updatesession();
	       wap_header();   
           wap_mes("<font color='green'>��¼�ɹ���</font>",'index.php?action=admin');
		   wap_footer();
       }else {   
	       wap_header();   
           wap_mes("<font color='red'>�û������������</font>",'');
		   wap_footer();
       }
   }
}

/** �û��������� */
if($View=='admin'){
	if(ISLOGIN){
       wap_header();
       if(ISLOGIN){
			 echo<<<DYHB
			 ������ʲô��?
<form method="post" action="index.php?action=addtaotao" >
<input name="ttcontent"/><br><input type="submit" value="������" /><br>
</form>
DYHB;
	     }
	   if(CheckPermission("cp","�����̨",'0')) echo "<a href=\"index.php?action=option\">��������</a><br>";
       if(CheckPermission("addentry","�㲻�ܷ�����־��",'0')) echo "<a href=\"index.php?action=addlog\">������־</a><br>";
	   echo<<<DYHB
	   <a href="index.php?action=user&id={$dyhb_userid}">�޸ĸ�����Ϣ</a><br>
	   <a href="index.php?login_out=true">�˳�</a>
DYHB;
	   wap_footer();
	}else{
	   wap_header();   
       wap_mes("<font color='red'>��û��Ȩ�޷��ʣ�</font>",'');
	   wap_footer();
	}
}

/** �������� */
if($View=='option'){
    if(CheckPermission("cp","�����̨",'0')){
		if(!$_POST[ok]){
		wap_header();
		echo<<<DYHB
		<form method="post" action="index.php?action=option">
		����:<br><input type="text" name="blog_title" value="{$dyhb_options[blog_title]}"/><br>
		����:<br><input type="text" name="blog_information" value="{$dyhb_options[blog_information]}"/>
		<input type="submit" name="ok" value="�޸�">
		</form>
DYHB;
		 wap_footer();
		}else{
		   $blogtitle=$_POST[blog_title];
		   $description=$_POST[blog_information];
           $_Cools->UpdateOption('blog_title',GbkToUtf8($blogtitle,''));
		   $_Cools->UpdateOption('blog_information',GbkToUtf8($description,''));
		   //����
           CacheOptions();
           wap_header();   
           wap_mes("<font color='green'>�������ø��³ɹ���</font>",'index.php?action=option');
	       wap_footer();
		}
	}else{
	   wap_header();   
       wap_mes("<font color='red'>��û��Ȩ�޷��ʣ�</font>",'');
	   wap_footer();
	}
}

/** �������� */
$UpdUserid=$_GET['id'];
if($View=='user'&&$UpdUserid){
    if(ISLOGIN){
		if(!$_POST[ok]){
		wap_header();
		echo<<<DYHB
		Welcome $dyhb_username!<br>
		<form method="post" action="index.php?action=user&id={$UpdUserid}">
		�ǳ�:<br><input type="text" name="coolname" value="{$dyhb_nikename}"/><br>
        ��ҳ:<br><input type="text" name="homepage" value="{$dyhb_homepage}"/>
		<input type="submit" name="ok" value="�޸�">
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
           wap_mes("<font color='green'>������Ϣ���³ɹ���</font>",'index.php?action=user&id={$UpdUserid}');
	       wap_footer();
		}
	}else{
	   wap_header();   
       wap_mes("<font color='red'>��û��Ȩ�޷��ʣ�</font>",'');
	   wap_footer();
	}
}

/** �û���� */
if($View=='photo'){
     if(CheckPermission("viewphotolist","�鿴������ᣡ",'0')){
	 wap_header();
     $photosort_id=intval( get_args('di') );//������
     $photo_id=intval( get_args('id') );//������Ƭ
     $PhotoSort=$_sidePhotoSorts;//������
     $JustShow=get_args('show');
     //��������Ϣ
     if($JustShow){ $MessageHead="����б�&nbsp;<a href=\"?action=photo\">��Ƭ�б�</a>";}
	 elseif($photosort_id){ $MessageHead="��Ƭ�б�&nbsp<a href=\"?action=photo\">����б�</a>"; }
	 elseif($photo_id){ $MessageHead="<a href=\"?action=photo&show=photosort\">����б�</a>&nbsp<a href=\"?action=photo\">������Ƭ</a>&nbsp;������Ƭ";}
     else{ $MessageHead="������Ƭ&nbsp;<a href=\"?action=photo&show=photosort\">����б�</a>";}
     echo "<div class=\"photohead\">{$MessageHead}</div>";

	 /** ��Ƭ�б� **/
      if(!$photo_id&&!$JustShow){
		 //��Ƭ�����ѯ
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
			   //������С
               $max_file_wh=unserialize($dyhb_options['wapfilelist_width_height']);
               $now_wh=ChangeImgSize("../width/upload/".$value['path'],$max_file_wh['0'],$max_file_wh['1']);
	           $the_img_url="3gimg.php?id=$value[file_id]&w=$now_wh[w]&h=$now_wh[h]";
               $_result.=<<<DYHB
	           <li><img src="$the_img_url" border="0"/><br><a href="?action=photo&id=$value[file_id]"><span>�鿴��ͼ</span></a></li>
DYHB;
           }
	   }else{
	         $_result.="�÷���û�з����κ���Ƭ��";
       }
	    $_result.="</ul></div><div id=\"pagenav\">$pagination</div>";
     }

	/** ���������Ƭ */
    elseif($photo_id){
       //������Ƭ
       $ShowPhoto=$_Photosorts->GetIdFile($photo_id);
       //������С
       $max_file_wh=unserialize($dyhb_options['wapfileshow_width_height']);
       $now_wh=ChangeImgSize("../width/upload/".$ShowPhoto['path'],$max_file_wh['0'],$max_file_wh['1']);
       //��һ��,��һ��
       $PreFile=$_Photosorts->GetPreFile($photo_id);
       $NextFile=$_Photosorts->GetNextFile($photo_id);
       $Pre_c=$PreFile['file_id']?"&id=$PreFile[file_id]":'';
       $Next_c=$NextFile['file_id']?"&id=$NextFile[file_id]":'';
	   $the_img_url="3gimg.php?id=$ShowPhoto[file_id]&w=$now_wh[w]&h=$now_wh[h]";
       $_result.=<<<DYHB
            <div class='onephoto'><div class="prephoto"><a href="?action=photo{$Pre_c}" >��һ��</a></div><a href="$the_img_url" title="$ShowPhoto[name]" target="_blank"><img src="$the_img_url"  style="border:none;"/></a><div class="nextphoto"><a href="?action=photo{$Next_c}">��һ��</a></div></div>
DYHB;
    }

	/** ����б� */
    elseif($JustShow=='photosort'){
      //������
      $PhotoSort['-1']=array('photosort_id'=>'-1','name'=>'δ����','cover'=>'','compositor'=>'0');
      $_result.="<div class='photolist'><ul>";
      if($PhotoSort){
           foreach($PhotoSort as $value){
               $Result=$_Photosorts->GetSorts($value[photosort_id]);//�����棬û�������ϵͳĬ�ϵ�ͼƬ
               $cover=$Result[cover]?$Result[cover]:$dyhb_options[blogurl]."/images/other/photosort.gif";
			   //�����С
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
       wap_mes("<font color='red'>��û��Ȩ�޷��ʣ�</font>",'');
	   wap_footer();
	} 
}

/** ��־�б����ݲ�ѯ */
if($View==''){
	 //�ж�����
     $tag = intval( get_argget('t') );
     $sort_id = intval( get_argget('s') );
     $record = intval( get_argget('r') );
     $user_id = intval( get_argget('u') );

   /** ͨ����ǩ������־*/
   if($tag){
	    $BlogIdStr=$_Tags->GetBlog_idStr($tag);
        if($BlogIdStr === false){
			wap_header();
		    wap_mes("<font color=\"red\"><b>û����־����</b></font>","");
		    wap_footer();
        }
        $Sql="and `blog_id` in (".$BlogIdStr.") and isshow='1' ORDER BY `istop` DESC,`dateline` DESC";
        $TotalLogNum=$DB->getresultnum("SELECT count(blog_id) FROM `".DB_PREFIX."blog` where `blog_id` in (".$BlogIdStr.") and `isshow`='1' and `ispage`='0'");
		$the_url="index.php?t=$tag";
   }
   /** ͨ�����������־ */
   elseif($sort_id){
      $Sql="and `sort_id`='$sort_id' ORDER BY `istop` DESC,`dateline` DESC";
      $TotalLogNum=$DB->getresultnum("SELECT count(blog_id) FROM `".DB_PREFIX."blog` where `sort_id`='$sort_id' $isshow and `ispage`='0'");
	  $the_url="index.php?s=$sort_id";
  }
  /** ͨ���û�������־ */
  elseif($user_id){
  	 $Sql="and `user_id`=".$user_id ."\nORDER BY `istop` DESC,`dateline` DESC";
     $TotalLogNum=$DB->getresultnum("SELECT count(blog_id) FROM `".DB_PREFIX."blog` where `user_id`='$user_id' $isshow and `ispage`='0'");
	 $the_url="index.php?u=$user_id";
  }
  /** ͨ���鵵������־ */
  elseif($record){
     $SqlTime=dyhb_date($record);
     $Sql="and `dateline` between\n" .$SqlTime[0]."\nand\n".$SqlTime[1]." $isshow order by `istop` desc,`dateline` desc";
     $TotalLogNum=$DB->getresultnum("SELECT count(blog_id) FROM `".DB_PREFIX."blog` where `dateline` between\n".$SqlTime[0]."\nand\n".$SqlTime[1]."\nand `isshow`='1' and `ispage`='0'");
	 $the_url="index.php?r=$record";
  }

  /** ��ҳ������־�б� */
  else{
      $Sql="order by `istop` desc,`dateline` desc";
      $TotalLogNum=$DB->getresultnum("SELECT count(blog_id) FROM ".DB_PREFIX."blog where `ispage`='0' $isshow");
	  $the_url="index.php";
  }

  /** ��������������ѯ���ݿ⣬������־�б����� */
  if($TotalLogNum){
       Page($TotalLogNum,$dyhb_options['mobile_log_num'],$the_url);
       $AdminLogs=$_Logs->GetLog($Sql,$pagestart,$dyhb_options[mobile_log_num],$isshow='1',$ispage='0');
  }

  //wap����
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
����:$value[commentnum] �Ķ�:$value[viewnum] 
DYHB;
if( ISLOGIN ){
echo<<<DYHB
<a href="index.php?action=longlog&id=$value[blog_id]">��д</a>
<a href="index.php?action=dellog&id=$value[blog_id]">ɾ��</a>
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

/** ������ʾҳ�� */
if($View=='show'){
	$newpage = intval( get_argget('newpage') );
	$blog_id = intval( get_argget('id') );
if($blog_id){
	$IsMobile=true;//����ȷ���Ƿ�Ϊ�ֻ��汾,��ǩ�ͷ�ҳ��Ҫ�õ�
    /** ��ȡ��־ */
    $ShowLog=$_Logs->GetOneLog($blog_id);
	$blog_id=$ShowLog['blog_id'];
	/** ���ӵ�� */
	$_Logs->AddView($blog_id);

    /** ��־��ҳ��.��־��ҳ���� */
	 $content_a=BreakOneLog();
     $NewPagination=count($content_a)==2?"":$content_a['newpagenav'];
	 $ShowLog['content']=$content_a[0];
	 if($newpage){
	      $ShowLog['content']=$content_a[($newpage-1)];
	 }

	/** ��һƪ,��һƪ */
   $PreLog=$_Logs->GetPreLog($blog_id);
   $NextLog=$_Logs->GetNextLog($blog_id);
    $dateline=date('Y-m-d H:i',$ShowLog['dateline']);
	//$content=html2text($ShowLog['content']);
	$content=strip_tags($ShowLog['content']);
	$content=$ShowLog[ismobile]=='1'?this_is_mobile($ShowLog[content],'2'):$ShowLog[content];
	$title=$ShowLog[ismobile]=='1'?this_is_mobile($ShowLog[title],'1'):$ShowLog[title];

	/** ��ʼ */
	wap_header();
	if($PreLog){
	   $prelog_url="<a href=\"index.php?action=show&id=$PreLog[0]\">$PreLog[1]</a>";
	}else{
	   $prelog_url="û����";
	}
	if($NextLog){
	   $nextlog_url="<a href=\"index.php?action=show&id=$NextLog[0]\">$NextLog[1]</a>";
	}else{
	   $nextlog_url="û����";
	}
	$tag=$ShowLog[tags]?"<div class=\"tags\">Tag:$ShowLog[tags]</div>":'';
	echo<<<DYHB
<div id="m">
	<div class="posttitle">$title</div>
	<div class="postinfo">post by:<a href='index.php?u=$ShowLog[user_id]'>$ShowLog[user]</a><br>����:<a href="index.php?s=$ShowLog[sort_id]">$ShowLog[name]</a><br>Time:$dateline<br><div class="tags">$tag</div>
DYHB;
	if(ISLOGIN) {
	echo<<<DYHB
	����:(<a href="index.php?action=longlog&id=$blog_id">��д</a>.<a href="index.php?action=dellog&id=$blog_id">ɾ��</a>)<br>
DYHB;
	}
	echo<<<DYHB
	</div>
	<div class="postcont">$content</div>
	<div id="page">$NewPagination</div>
	<div class="t">�鿴����:<a href='index.php?action=com&id=$blog_id'>($ShowLog[commentnum])</a></div>
	<div class="prelog">��һƪ:$prelog_url</div>
	<div class="nextlog">��һƪ:$nextlog_url</div>
</div>
DYHB;
	wap_footer();
}
}

/** ���۵�����ʾҳ�棬������־ҳ������������ */
if($View=='com'){   
	$blog_id = intval( get_argget('id') );
	if($blog_id){
	/** �ֻ��汾��Ƕ������ */
   $TotalComment=$DB->getresultnum("SELECT count(comment_id) FROM `".DB_PREFIX."comment` where `blog_id`='$blog_id' and `isshow`='1'");
   if($TotalComment){
        Page($TotalComment,$dyhb_options['mobile_comment_num']);
	    $ShowComment=$_Comments->GetComment("order by `comment_id` desc",$blog_id,$isshow=1,$pagestart,$dyhb_options['mobile_comment_num'],'0','0','0');
   }
	 wap_header();
	 echo<<<DYHB
     <p><a href="index.php?action=show&id=$blog_id">������־</a></p>
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
<div class="t">�������ۣ�</div>
	<div class="c">
		<form method="post" action="index.php?action=addcom">
		<input name="blog_id"  type="hidden" value="{$blog_id}" />
DYHB;
	    if(ISLOGIN){
			echo<<<DYHB
		��ӭ�㣬$dyhb_username<input type="hidden" name="name" value="$dyhb_username"/>
	    <input type="hidden" name="email" value="$dyhb_email"/>
		<input type="hidden" name="url" value="$dyhb_homepage"/><br />
DYHB;
		}
		else{
			echo<<<DYHB
		�ǳ�<br /><input type="text" name="name"/><br />
DYHB;
		}
echo<<<DYHB
	    <input type="text" name="comment" value=""/><br />
		<input type="submit" value="��������" />
		</form>
</div>
DYHB;
	 wap_footer();
	}
}

//��־����
function cacheaction(){
 global $dyhb_options;
 //���»���
cachenewlog();
cachehotlog();
cacheyearhotlog();
cachemouthhotlog();
CacheFlashLog();
}

/** ��д��־����֧�ֱ༭��־ */
if($View=='longlog'){
	if(!CheckPermission("editentry","�㲻�ܱ༭��־��",'0')){
	     wap_header();
         wap_mes("<font color='red'>��û��Ȩ�޼ӳ���־</font>",'');
         wap_footer();
	}
    $blog_id = intval ( $_GET[id] );
    $UpdLog=$_Logs->GetOneLog ( $blog_id );
	if($dyhb_userid==$UpdLog[user_id]){
		if(!CheckPermission("editotherentry","�㲻�ܱ༭���˵���־��",'')){
	     wap_header();
         wap_mes("<font color='red'>��û��Ȩ�ޱ༭���˵���־</font>",'');
         wap_footer();
	  }
	}
}

/** �����־ */
if($View=="addlog"||$View=="longlog"){
  if(!CheckPermission("addentry","�㲻�ܷ�����־��",'')){
     wap_header();
         wap_mes("<font color='red'>��û��Ȩ�޷�������־</font>",'');
         wap_footer();
  }
   wap_header();
   echo<<<DYHB
<div id="m">
<form action="index.php?action=savelog" method="post">
���⣺<br /><input type="text" name="title" value="$UpdLog[title]" /><br />
���ࣺ<br />
	      <select name="sort_id" id="sort">
DYHB;
			$_sideSorts[] = array('sort_id'=>-1, 'name'=>'ѡ�����...');
			foreach($_sideSorts as $val){
			$flg = $val['sort_id'] == $UpdLog[sort_id] ? 'selected' : '';
		    echo<<<DYHB
			<option value="$val[sort_id]" $flg>$val[name]</option>
DYHB;
			}
echo<<<DYHB
	      </select>
<br />
���ݣ�<br /><input type="text" name="content"/><br />
<input type="hidden" name="old_content" value="$UpdLog[content]"/>
<input type="hidden" name="blog_id" value="$UpdLog[blog_id]"/>
<input type="submit" value="������־" />
</form>
</div>  
DYHB;
	wap_footer();
}

/** ������־���� */
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
	    wap_mes("<font color='red'>����������ݲ���Ϊ��</font>",$the_url);
	    wap_footer();
	 }

     //$blog_id>0,��д��־
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
	
	//��������
	if($blog_id>0){
		$_Logs->UpdateLog(GbkToUtf8($SaveLogDate,''),$blog_id);
	}else{
	    $blog_id=$_Logs->AddLog(GbkToUtf8($SaveLogDate,''));
	}
	//���»���
cacheaction();
if($dyhb_options['cache_cms_log']=='1'){
  CacheTag();
  CacheCmsNew();
  CacheCmsSort();
}
	wap_header();
	wap_mes("<font color='green'>������־�ɹ���</font>","index.php?action=show&id=$blog_id");
	wap_footer();
}

/** ɾ����־ */
if($View=='dellog'){
	if(!CheckPermission("cp","ɾ����־��",'0')){
	     wap_header();
         wap_mes("��û��Ȩ��ɾ����־</font>",'');
         wap_footer();
	 }
    $blog_id = intval ( $_GET[id] );
    $UpdLog=$_Logs->DeleteLog ( $blog_id );
	//���»���
cacheaction();
if($dyhb_options['cache_cms_log']=='1'){
  CacheTag();
  CacheCmsNew();
  CacheCmsSort();
}
	wap_header();
	wap_mes("<font color='green'>ɾ����־�ɹ���</font>","index.php");
	wap_footer();
}

/** �����б� */
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
	 if(CheckPermission("cp","��������Ȩ�ޣ�",'0')){
	    $YouCan=true;
	 }
	$ishide = $YouCan === true && $value['isshow']=='0'?'<font color="red" size="1">[����]</font>':'';
	if($value[file_id]!='0'){
	   $torelation="<font color='green'>�������</font>";
	}elseif($value[taotao_id]!='0'){
	   $torelation="<font color='blue'>��������</font>";
	}elseif($value[mp3_id]!='0'){
	   $torelation="<font color='yellow'>��������</font>";
	}elseif($value[blog_id]!='0'){
	   $torelation="<a href='index.php?action=show&id=$value[blog_id]'><font color='purple'>��־����</font></a>";
	}else{
	   $torelation="<font color='#44d9f9'>��������</font>";
	}
    $dateline=date('Y-m-d H:i',$value[dateline]);
	$comment=$value[ismobile]=='1'?this_is_mobile(strip_tags($value[comment]),'2'):strip_tags($value[comment]);
	$mobile_title=$value[ismobile]=='1'?this_is_mobile('','1'):'';
    echo<<<DYHB
<div class="comcont">{$mobile_title}{$comment}$ishide
DYHB;
if(ISLOGIN){
	echo<<<DYHB
<a href="index.php?action=delcom&id=$value[comment_id]"><font size="1">[ɾ��]</font></a>
DYHB;
}
echo<<<DYHB
</div>
<div class="info">�������ԣ�$torelation</div>
<div class="cominfo">
DYHB;
if($YouCan){
	echo<<<DYHB
<a href="index.php?action=hidecom&id=$value[comment_id]">����</a>
<a href="index.php?action=showcom&id=$value[comment_id]">���</a>
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

/** ������� */
if($View=='addcom'){
     if(!CheckPermission("leavemessage","������ۣ�",'0')){
	     wap_header();
         wap_mes("<font color='red'>��û��Ȩ���������</font>",'');
         wap_footer();
	 }
	 //��������
	 $blog_id = intval ( get_argpost('blog_id') );
	 $name = sql_check( get_argpost('name') );
	 $comment = strip_tags(sql_check( get_argpost('comment') ));
	 $url = sql_check( get_argpost('url') );
	 $email = sql_check( get_argpost('email') );
	 $ip=getIp();
	 if($name==''||$comment==''){
		 wap_header();
		 wap_mes("<font color=\"red\"><b>���ֻ����ݲ���Ϊ�ա�</b></font>","");
		 wap_footer();
	 }
	 //��������
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
	 wap_mes("<font color='green'>�������۳ɹ���</font>>",'index.php?action=com&id='.$blog_id);
	 wap_footer();
 }


//ɾ������
if($View=='delcom'){
   if(!CheckPermission("leavemessage","������ۣ�",'0')){
	     wap_header();
         wap_mes("��û��Ȩ���������",'');
         wap_footer();
	 }
	 $Comment_id=intval( $_GET[id] );
  $_Comments->DeleteComment($Comment_id );
  CacheNewComment();
	CacheNewGuestbook();
  wap_header();
  wap_mes("<font color='green'>ɾ�����۳ɹ���</font>",'index.php?action=comment');
  wap_footer();
}

//��������
if($View=='hidecom'){
   if(!CheckPermission("leavemessage","������ۣ�",'0')){
	     wap_header();
         wap_mes("��û��Ȩ���������",'index.php?action=comment');
         wap_footer();
	 }
	 $Comment_id=intval( $_GET[id] );
	 $_Comments->HideShowComment('0',$Comment_id);
	 CacheNewComment();
	CacheNewGuestbook();
	 wap_header();
         wap_mes("<font color='green'>�������۳ɹ���</font>",'index.php?action=comment');
         wap_footer();
}

//�������
if($View=='showcom'){
   if(!CheckPermission("leavemessage","������ۣ�",'0')){
	     wap_header();
         wap_mes("��û��Ȩ���������</div>",'');
         wap_footer();
	 }
	 $Comment_id=intval( $_GET[id] );
	 $_Comments->HideShowComment('1',$Comment_id );
	 CacheNewComment();
	CacheNewGuestbook();
	 wap_header();
         wap_mes("<font color='green'>������۳ɹ���</font>",'index.php?action=comment');
         wap_footer();
}

 /** ���� */
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

 /** ��ǩ */
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

 /** �������� */
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
				  $del=ISLOGIN===true?"<a href=\"index.php?action=deltaotao&id=$value[taotao_id]&uid=$value[user_id]\">ɾ��</a>":'';
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

   
//�������
if(ISLOGIN&&$View=='addtaotao'){
	     $TtContent = GbkToUtf8(sql_check( get_argpost('ttcontent') ),'');
	     if (!empty($TtContent)){
		       $query = $DB->query("INSERT INTO `".DB_PREFIX."taotao` (content,user_id,dateline,ismobile) VALUES('$TtContent','".$dyhb_userid."','$localdate','1')");
	     }
	    CacheSideTao();
	    wap_header();
        wap_mes("<font color='green'>��������ɹ���</font>",'index.php?action=admin');
        wap_footer();
}

//ɾ������
if($View=='deltaotao'){
	 if(!CheckPermission("cp","ɾ�����飡",'0')){
	     wap_header();
         wap_mes("��û��Ȩ��ɾ��",'index.php?action=taotao');
         wap_footer();
	 }
     $taotao_id= intval( get_argget('id') );
     $UserId =  intval( get_argget('uid') );
     $DB->query("DELETE FROM `".DB_PREFIX."taotao` WHERE `taotao_id`=$taotao_id and `user_id` ='$UserId'");
     CacheSideTao();
     wap_header();
     wap_mes("<font color='green'>ɾ������ɹ���</font>",'index.php?action=taotao');
     wap_footer();
}

?>