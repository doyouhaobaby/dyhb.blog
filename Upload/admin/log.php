<?php
/**================[^_^]================**\
        ---- ���������������� ----
@----------------------------------------@
        * �ļ���log.php
        * ˵������־����
        * ���ߣ�Сţ��
        * ʱ�䣺2010-05-06 20:22
        * �汾��DoYouHaoBaby-blog �����
        * ��ҳ��www.doyouhaobaby.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

//��־�б����
if($view==''||$view=='ishide'||$view=='ispage'||$view=='list'){
$tag_id = intval( get_argget('tag_id')) ;
$user_id =intval( get_argget('user_id'));
$sortviewnun='asc';
$sortdate='desc';
$sortviewnum=sql_filter( sql_check(CheckSql(get_argget('sortviewnum')))) == 'asc' ?  'desc' : 'asc';
$sortdate = sql_filter( sql_check(CheckSql(get_argget('sortdate'))) )== 'desc' ?  'asc' : 'desc';
$sort_id =intval( get_argget('sort_id'));
$status =intval( get_argget('status'));
$keyword =get_argget('keyword') ;

//����׫д��ע���û��ĸ�����־
$sqlmore='';
if(($dyhb_usergroup=='2'||$dyhb_usergroup=='3')){
    $sqlmore="and `user_id`='$dyhb_userid'";
}

//��������
$Sql = '';
if($tag_id){$blog_idStr=$_Tags->GetBlog_idStr($tag_id);
  if(!$blog_idStr){DyhbMessage($common_func[232],'');}
    $Sql = "and blog_id IN ($blog_idStr) $sqlmore";
}
elseif ($sort_id){
	$Sql .= "and `sort_id`='$sort_id' $sqlmore";	
}
elseif ($user_id){$Sql = "and `user_id`=$user_id";}
else { $Sql=$sqlmore; }

if($keyword){
	$Sql.="and `title` like '%$keyword%'";
}

//����
$Sql.= ' ORDER BY ';
if(isset($_GET['sortviewnum'])){$Sql.= "`viewnum` $sortviewnum";}
elseif(isset($_GET['sortdate'])){$Sql .= "`dateline` $sortdate";}
else {$Sql .= 'istop DESC,dateline DESC';}
//�Ƿ�Ϊҳ�棬�Ƿ���ʾ������
//ҳ��
if($view=="ispage"){$ispage=1;}
else{$ispage=0;}
//�ݸ�/����
if($view=="ishide"||$status=='2'){$isshow=0;}
else{$isshow='1';}

//��ѯ����
if($tag_id||$user_id||$sort_id){
   if($tag_id){$con="and `blog_id` in (".$blog_idStr.")";}
   elseif( $sort_id){
	   $con="and `sort_id`='$sort_id'";
   }
   if( $keyword ){
	   $con.="and `title` like '%{$keyword}%'";
   }
   $TotalLogNum=$DB->getresultnum("SELECT count(blog_id) FROM `".DB_PREFIX."blog` WHERE `isshow`='1' and `ispage`='0' $sqlmore $con");
}
elseif($view=="ispage"){$TotalLogNum=$DB->getresultnum("SELECT count(blog_id) FROM `".DB_PREFIX."blog` WHERE `isshow`='1' and `ispage`='1' $sqlmore");}
else{$TotalLogNum=$DB->getresultnum("SELECT count(blog_id) FROM `".DB_PREFIX."blog` WHERE `isshow`=$isshow $sqlmore");}
if($TotalLogNum){
   Page($TotalLogNum,$dyhb_options[admin_log_num]);
   $isAdmin=true;
   $AdminLogs=$_Logs->GetLog($Sql,$pagestart,$dyhb_options[admin_log_num],$isshow,$ispage);
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

$DelBlogId=intval( get_argget('id'));
if($view=="del"&&$DelBlogId){
	CheckPermission("cp",$common_permission[26]);
   $_Logs->DeleteLog($DelBlogId);
//���»���
cacheaction();
if($dyhb_options['cache_cms_log']=='1'){
  CacheTag();
  CacheCmsNew();
  CacheCmsSort();
}
DyhbMessage($common_func[245],'?action=log');
}
if($view=="prepare"){
	CheckPermission("cp",$common_permission[27]);
	$logs_array=isset($_POST['logs'])?$_POST['logs']:'';
	$blogact= CheckSql(sql_check ( get_argpost('prepare')));
	$sort_id =intval ( get_argpost('sort'));
	$user_id =intval ( get_argpost('author'));
	switch ($blogact){
	    case 'del':
		$_Logs->LogAction($logs_array,'del');
        break;
		case 'caogao':
		$_Logs->LogAction($logs_array,'caogao');
		break;
		case 'uncaogao':
		$_Logs->LogAction($logs_array,'uncaogao');
        break;
		case 'top':
		CheckPermission("topentry",$common_permission[28]);
		$_Logs->LogAction($logs_array,'top');
		break;
		case 'untop':
		CheckPermission("topentry",$common_permission[29]);
		$_Logs->LogAction($logs_array,'untop');
        break;
		case 'lock':
		$_Logs->LogAction($logs_array,'lock');
		break;
		case 'unlock':
		$_Logs->LogAction($logs_array,'unlock');
        break;
		case 'page':
		$_Logs->LogAction($logs_array,'page');
		break;
        case 'unpage':
		$_Logs->LogAction($logs_array,'unpage');
        break;
		case 'changesort':
		foreach($logs_array as $value){
		   $DB->query("update `".DB_PREFIX."blog` set `sort_id`=$sort_id where `blog_id`=$value");
		}
		case 'changeauthor':
		foreach($logs_array as $value){
		   $DB->query("update `".DB_PREFIX."blog` set `user_id`=$user_id where `blog_id`=$value");
		}
		break;
     }
	 cacheaction();
	 if($dyhb_options['cache_cms_log']=='1'){
	    CacheTag();
        CacheCmsNew();
        CacheCmsSort();
     }
     DyhbMessage($common_func[244],'?action=log');
}

//tag
if($view=="tag"){
    $TagId = intval( get_argget('id') );
   //tags
   $TotalTagNum=$DB->getresultnum("SELECT count(tag_id) FROM `".DB_PREFIX."tag`");
   if($TotalTagNum!=0) {
       Page($TotalTagNum,$dyhb_options[admin_tag_num]);
       $ShowTag=$_Tags->GetTag('',$pagestart,$dyhb_options[admin_tag_num]);
    }
   //����tag��ǩ
  if($TagId&&$view2=="upd"){
	 $UpdTag=$_Tags->GetOneTag($TagId);
  }
 
  //ɾ��tag��ǩ
  if($view2=="del"){
	  CheckPermission("cp",$common_permission[30]);
	  $tags_id=isset($_POST['tags'])?$_POST['tags']:'';
	  if( $tags_id ){
	     foreach($tags_id as $value){
		    $_Tags->DelTag($value);
	     }
		 //���¾�̬��
	    if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("tag");
		}
		 Header("location:?action=log&do=tag&deltag=true");
	  }else{
	     Header("location:?action=log&do=tag&errortag=true");
	  }
   }

  //���tag��ǩ
  if($view2=="save"){
	  CheckPermission("addtag",$common_permission[31]);
      $name=sql_check( get_argpost('name') );
	  $urlname=sql_check( get_argpost('urlname') );
	  $tag_id= intval( get_argpost('tag_id') );
	  $keyword = sql_check( get_argpost('keyword') );
	  $description = sql_check( get_argpost('description') );
	  if( $name=='' ){ Dyhbmessage("<font color=\"red\"><b>$common_func[233]</b></font>","-1"); }//�ж�
      if($DB->getonerow("SELECT tag_id FROM ".DB_PREFIX."tag WHERE tag_id != '".intval($tag_id)."' AND name = '$name'")){
	          Dyhbmessage("<font color=\"red\"><b>$common_func[234]</b></font>","-1");
	   }
	   if(!empty($urlname)){
		   if(!preg_match('/^[a-z0-9\-\_]*[a-z\-_]+[a-z0-9\-\_]*$/i',$urlname)){
		       DyhbMessage($common_func[235],'-1');
		   }
	       elseif($DB->getonerow("SELECT tag_id FROM ".DB_PREFIX."tag WHERE tag_id != '".intval($tag_id)."' AND urlname = '$urlname'")){
	          Dyhbmessage("<font color=\"red\"><b>$common_func[236]</b></font>","-1");
	       }
        }
		if($tag_id>0){
	     $_Tags->UpdTagName($tag_id,$name,$urlname,$keyword,$description);
		 //���¾�̬��
	    if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("tag");
		}
	     Header("location:?action=log&do=tag&do2=upd&id={$tag_id}&updtag=true");
	  }else{
	     $_Tags->AddTag($name,$urlname,'',$keyword,$description);
		 //���¾�̬��
	    if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("tag");
		}
	     Header("location:?action=log&do=tag&savetag=true");
       }
   }
    CacheTag();
}

//micorlog
if($view=='microlog'){
	CheckPermission("cp",$common_permission[32]);
    $MicrologId = intval( get_argget('id') );
	$UserId = intval( get_argget('uid') );
	$User_c=$UserId?"where `user_id`='$UserId'":'';
   //microlog
   $TotalMicrologNum=$DB->getresultnum("SELECT count(taotao_id) FROM `".DB_PREFIX."taotao`");
   if($TotalMicrologNum!=0) {
       Page($TotalMicrologNum,$dyhb_options[admin_log_num]);
       $ShowMicrolog=$DB->gettworow("select *from `".DB_PREFIX."taotao` order by dateline desc limit $pagestart,$dyhb_options[admin_log_num] ");
    }

   $i=0;
   if($ShowMicrolog){
   foreach($ShowMicrolog as $value){
      $user_c=$DB->getonerow("select `username`,`nikename` from	`".DB_PREFIX."user` where `user_id`='$value[user_id]'");
	  $ShowMicrolog[$i]['username']=$user_c['nikename']?$user_c['nikename']:$user_c['username'];
	  $ShowMicrolog[$i]['username']=$value[ismobile]=='1'?this_is_mobile($ShowMicrolog[$i]['username'],'1'):$ShowMicrolog[$i]['username'];
	  $ShowMicrolog[$i]['content']=strip_tags(stripslashes($value[content]));
      $i++;
   }
   }

   //ɾ��microlog
  if($view2=="del"){
	  $tags_id=isset($_POST['micrologs'])?$_POST['micrologs']:'';
	  if( $tags_id ){
	     foreach($tags_id as $value){
		    $DB->query("delete from `".DB_PREFIX."taotao` where `taotao_id`='$value'");
	     }
        //���¾�̬��
	    if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("microlog");
		}
		 Header("location:?action=log&do=microlog&delmicrolog=true");
	  }else{
	     Header("location:?action=log&do=microlog&errormicrolog=true");
	  }
   }

   if($view2=="del"&&$MicrologId){
       $DB->query("delete from `".DB_PREFIX."taotao` where `taotao_id`='$MicrologId'");
	   //���¾�̬��
	    if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("microlog");
		}
	   Header("location:?action=log&do=microlog&delOnemicrolog=true");
   }
   //���
   if($view2=='add'){
        CheckPermission("sendmicrolog",$common_permission[33],'');
		$TtContent =addslashes(get_argpost('content'));
		if(!CheckPermission("html","����ʹ��html���룡",'0')){
           $TtContent = strip_tags($TtContent);
		}
		if($TtContent){
		   $query = $DB->query("INSERT INTO ".DB_PREFIX."taotao (content,user_id,dateline) VALUES('$TtContent',".LOGIN_USERID.",'$localdate')");
	       CacheSideTao();
		}
		//���¾�̬��
	    if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("microlog");
		}
	    Header("location:?action=log&do=microlog&addmicrolog=true");
   }
}

//trackback
if($view=='trackback'){
  CheckPermission("cp",$common_permission[35]);
  //�����б����
  $TotalTraNum=$DB->getresultnum("SELECT count(trackback_id) FROM `".DB_PREFIX."trackback`");
  if($TotalTraNum){
      Page($TotalTraNum,$dyhb_options[admin_log_num]);
      $AdminTras=$_Trackbacks->GetTrackbacks('',$pagestart,$dyhb_options[admin_log_num],true);
    }

    //���ò���
    $DelTrabackId=intval( get_argget('id'));
    if($view2=="del"&&$DelTrabackId){
      if(LOGIN_USERGROUNP=='1'){
        $_Trackbacks->DelTrackback($DelTrabackId);
        //���»���
        CacheBlog();
        DyhbMessage($common_func[237],'?action=log&do=trackback');
      }else{
        DyhbMessage($common_func[238],'?action=log&do=trackback');
      }
  }

   if($view2=="prepare"){
   $trackbacks_array=isset($_POST['trackbacks'])?$_POST['trackbacks']:'';
   //�����������Ƿ����Ժ���������õ�
   //$trackbackact= isset($_POST['prepare']) ? $_POST['prepare'] : '';
      foreach($trackbacks_array as $value){
         $_Trackbacks->DelTrackback($value);
     }
     CacheBlog();
     DyhbMessage($common_func[243],'?action=log&do=trackback');
   }
}

if($view=='add'){
    CheckPermission("addentry",$common_permission[36]);
}

//��ȡ�����Զ����ֶ�
$Allfield=$DB->getthefield('blog');
$Bloghavefield=array("blog_id","title","dateline","content","from","fromurl","urlname","user_id","sort_id","thumb","viewnum","password","istop","isshow","islock","ispage","trackbacknum","istrackback","commentnum","good","bad",'ismobile','keyword','description');
$Yourfield=findArray($Allfield,$Bloghavefield);

//�ֶι���
if($view=='myfield'){
	IsSuperAdmin($common_permission[37],'');
    //ɾ���ֶ�
	$fieldname=get_argget('name');
	if($view2=='del'&&$fieldname){
        $DB->query("ALTER TABLE `".DB_PREFIX."blog` DROP `$fieldname`");
		header("location:?action=log&do=myfield&delfield=true");
	}
	//�����ֶ�
	if($view2=='save'){
	    $the_fieldname=get_args('fieldname');
		if($the_fieldname==''){
		    DyhbMessage($common_func[239],'');
		}
		$old_fieldname=get_args('old_fieldname');
		if($old_fieldname!=''&&$old_fieldname!=$the_fieldname){
			 if(!@in_array($fieldname,$Allfield)){
				  //�����ֶ�
				  $DB->query("ALTER TABLE `".DB_PREFIX."blog` CHANGE `$old_fieldname` `$the_fieldname` TEXT CHARACTER SET gbk COLLATE gbk_chinese_ci NOT NULL ");
				  header("location:?action=log&do=myfield&updfield=true");
			 }
		}else{
			if(!@in_array($the_fieldname,$Allfield)){
		        //����µ��ֶ�
                $DB->query("ALTER TABLE `".DB_PREFIX."blog` ADD `$the_fieldname` TEXT CHARACTER SET gbk COLLATE gbk_chinese_ci NOT NULL");
			}
			header("location:?action=log&do=myfield&addfield=true");
		}
	}
	//�༭�ֶ�
	if($view2=='upd'&&$fieldname){
	    
	}
}

$_Bloggers=$DB->gettworow("SELECT *FROM ".DB_PREFIX."user");
//����&�༭��־
if($view=="save"){
     //��־����
	 $blog_id =intval ( get_args('blog_id'));
	 $user_id =intval ( get_args('user_id'));
	 $user_id=$user_id>0?$user_id:LOGIN_USERID;
	 $keyword = sql_check( get_args('keyword') );
	 $description = sql_check( get_args('description') );
	 $title =  get_args('title');
     $dateline = sql_check ( get_args('dateline'));
     $dateline=mktime(substr($dateline,11,2),substr($dateline,14,2),substr($dateline,17,2),substr($dateline,5,2),substr($dateline,8,2),substr($dateline,0,4));
     if($dateline==''){$dateline=$localdate;}
	 /*��־������*/
	 $content =  get_args('content');
	 $content=str_replace($dyhb_options[blogurl]."/width/upload","width/upload",$content);//����·������,��ֹ��Ϊ������������ļ�ʧЧ
	 $content=str_replace($dyhb_options[blogurl]."/file.php?id=","file.php?id=",$content);//����·������,��ֹ��Ϊ������������ļ�ʧЧ
	 $content=str_replace("editor/xheditor_emot/",'admin/editor/xheditor_emot/',$content);//����

	 //����Զ��ͼƬ
	 $is_allow_tolocalimg=intval ( get_args('is_allow_tolocalimg')) ;
	 //exit($is_allow_tolocalimg);
	 if($dyhb_options['is_allow_tolocalimg']=='1'||$is_allow_tolocalimg=='1') {$content=ImgToLocal($content);}

	 $urlname = sql_check ( get_args('urlname'));
	 $from = sql_check ( get_args('from'));
     $fromurl= sql_check ( get_args('fromurl'));
     $sort_id = intval ( get_args('sort_id'));
     $thumb = sql_check ( get_args('thumb'));
	 $thumb=str_replace($dyhb_options[blogurl]."/width/upload","width/upload",$thumb);//����·������,��ֹ��Ϊ������������ļ�ʧЧ
     $islock = intval ( get_args('islock'));
     $isshow =intval ( get_args('isshow'));
	 //�Զ�����
     if($_GET['type']=='autosave'){$isshow=0;}
	 $ispage=intval ( get_args('ispage')) ;
	 $istop=intval ( get_args('istop')) ;
     $password =sql_check ( get_args('password')) ;
	 $tag =sql_check ( get_args('tag'));
	 $istrackback =intval( get_args('istrackback'));
	 $pingurl=sql_check ( get_args('pingurl')) ;
	 //������־
     if($blog_id>0&&$_GET['type']!='autosave'){$isshow='1';}
	 //�����û��Ȩ�޷��ʲݸ���û�����ǿ�й���������־
	 if(!CheckPermission("editentry",$common_permission[38],'0')||!CheckPermission("cp",$common_permission[39],'0')){
	     $isshow='1';
	 }
	 //����ʽ
	 if($fromurl){
	   isurl($fromurl);
	 }
	 //�������Ƿ��ظ�
	 if(!empty($urlname)){
	   if(!preg_match('/^[a-z0-9\-\_]*[a-z\-_]+[a-z0-9\-\_]*$/i',$urlname)){
	      DyhbMessage($common_func[235],'-1');
	   }
	   elseif($DB->getonerow("SELECT `blog_id` FROM ".DB_PREFIX."blog WHERE `blog_id` != '".intval($blog_id)."' AND `urlname` = '$urlname'")){
		  DyhbMessage($common_func[240],'-1');
	   }
	 }
   
     //��־����
     $SaveLogDate=array(
		 'blog_id'=>$blog_id,
		 'title'=>$title,
		 'dateline'=>$dateline,
		 'content'=>$content,
		 'urlname'=>$urlname,
		 'from'=>$from,
		 'fromurl'=>$fromurl,
		 'user_id'=>$user_id,
		 'sort_id'=>$sort_id,
		 'thumb'=>$thumb,
		 'password'=>$password,
		 'istop'=>$istop,
		 'isshow'=>$isshow,
		 'islock'=>$islock,
		 'ispage'=>$ispage,
		 'istrackback'=>$istrackback,
		 'keyword'=>$keyword,
		 'description'=>$description
	     );

	 //�Զ����ֶα���ֵ
	 if($Yourfield){
	     foreach($Yourfield as $value){
		     $SaveLogDate[$value]=sql_check( get_args($value."_value"));
		 }
	 }

     if(IsSuperAdmin($common_permission[37],'0')){
	 //��������Ա���ܹ��������ֶ�
	 //���ֶ����
	 $new_field_name=sql_check ( get_args('new_field') );
	 if($new_field_name!=''&&!@in_array($new_field_name,$Allfield)){
	     //����µ��ֶ�
         $DB->query("ALTER TABLE `".DB_PREFIX."blog` ADD `$new_field_name` TEXT CHARACTER SET gbk COLLATE gbk_chinese_ci NOT NULL");
		 $new_field_value=sql_check ( get_args('new_field_value') );
		 //�������ֶε�ֵ
		 if($new_field_value!=''){
		      $SaveLogDate[$new_field_name]=$new_field_value;
		 }
	 }
	 }

	 //�Զ�����(������)
	 if($_GET['type']=='autosave'){
	     $SaveLogDate=GbkToUtf8($SaveLogDate,'');
		 $tag=GbkToUtf8($tag,'');
	 }

	 if($blog_id>0){//������־
		 $_Logs->UpdateLog($SaveLogDate,$blog_id);
		 $_Tags->UpdTag($tag,$blog_id,'');
		 if($_GET['type']=='autosave'){
		   echo $blog_id;
        }
	 }
	 else{//������־
		   $blog_id=$_Logs->AddLog($SaveLogDate);
		   $_Tags->AddTag($tag,'',$blog_id,'','');
		   if($_GET['type']=='autosave'){
		      echo $blog_id;
           }
	 
	}

	//������־������չ
    doHooks('add_log',$blog_id);

   if(!empty($pingurl)){
	   //��������
	   $tmes=$_Trackbacks->SendTrackback(GbkToUtf8($dyhb_url,'GBK'), $pingurl, $blog_id, GbkToUtf8($title,'GBK'), GbkToUtf8($dyhb_options[blog_title],'GBK'), GbkToUtf8($content,'GBK'));
   }

 if($_GET['type']!='autosave'){
	  cacheaction();
	  if($dyhb_options['cache_cms_log']=='1'){
	    CacheTag();
        CacheCmsNew();
	    if($sort_id!='-1'&&$sort_id!=''){
			$s_value=$_Sorts->GetIdSort($sort_id);
			cacheSort($s_value);
		}
    }
    if($tmes){
		$tmes="$common_func[241]<br>".GbkToUtf8($tmes,'');
        set_cookie('blog_'.$blog_id,$tmes,'864000');
	}
    
	//���¾�̬��
	 if( $dyhb_options[allowed_make_html]=='1'){
	    //��ȡ��־����
	    $the_log=$_Logs->GetOneLog( $blog_id );
	    MakePostHtml( $the_log);
	 }

	 Header("location:?action=log&do=upd&id=$blog_id&save=true");
  }
}
//�༭��־
$UpdBlogId=intval( get_argget('id')) ;
if($view=='upd'&&$UpdBlogId){
	CheckPermission("editentry",$common_permission[38]);
	$trackback_back_mes=get_cookie('blog_'.$UpdBlogId);
	//ɾ��COOKIE
	set_cookie('blog_'.$UpdBlogId,'','-86400');
	//��־
	$UpdLog=$_Logs->GetOneLog($UpdBlogId);
	if($dyhb_userid!=$UpdLog['user_id']){
	    CheckPermission("editotherentry",$common_permission[40]);
	}
	$UpdLog[content]=str_replace("width/upload","$dyhb_options[blogurl]/width/upload",stripslashes($UpdLog[content]));
	$UpdLog[content]=str_replace("file.php?id=","$dyhb_options[blogurl]/file.php?id=",$UpdLog[content]);
	$UpdLog[content]=str_replace("admin/editor/xheditor_emot/",'editor/xheditor_emot/',$UpdLog[content]);//����
    $UpdLog['from']=stripslashes($UpdLog['from']);
    $_sideSorts[]=array('sort_id'=>-1, 'name'=>$common_func[242],'');
    $UpdTag=$_Tags->GetTag($UpdBlogId,'','');
    $tagStra=array();
    foreach($UpdTag as $value){
       $tagStra[]=$value["name"];
    }
    $UpdTagStr=implode("��",$tagStra);

}

include DyhbView('log',1);

?>