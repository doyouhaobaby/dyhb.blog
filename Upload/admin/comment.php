<?php
/**================[^_^]================**\
        ---- ���������������� ----
@----------------------------------------@
        * �ļ���comment.php
        * ˵�������۹���
        * ���ߣ�Сţ��
        * ʱ�䣺2010-05-06 20:22
        * �汾��DoYouHaoBaby-blog �����
        * ��ҳ��www.doyouhaobaby.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

CheckPermission("cp",$common_permission[43]);

//�����б����
$CommentId = intval( get_argget('id'));

//����
if($view2=='commentid_asc'){
	$Sql="order by `comment_id` asc";
	$the_url="&do2=comentid_asc";
}elseif($view2=='commentid_desc'){
    $Sql="order by `comment_id` desc";
	$the_url="&do2=comentid_desc";
}
elseif($view2=='dateline_asc'){
    $Sql="order by `dateline` asc";
	$the_url="&do2=dateline_asc";
}
elseif($view2=='dateline_desc'){
    $Sql="order by `dateline` desc";
	$the_url="&do2=datelie_desc";
}else{
    $Sql="order by `dateline` desc,`comment_id` desc";
	$the_url='';
}

//�Ƿ�Ϊ��ʾ����־
if($view2=='show'){
	$isshow='1';
}
elseif($view2=='hidden'){
	$isshow='0';
}else{
    $isshow='';
}

//��ʾ��������
if($isshow!=''){
   if($view=='blog'||$view=='mp3'||$view=='photo'||$view=='taotao'||$view=='guestbook'){
        $show_con="and `isshow`=$isshow";
   }else{
        $show_con="where `isshow`=$isshow";
   }
}else{
    $show_con='';
}

/** �������� */
if($view=='blog'){
    //��־����
	$CommentTotal=$DB->getresultnum("SELECT count(comment_id) FROM `".DB_PREFIX."comment` where `blog_id`!='0' $show_con");
	$CommentType_c="where `blog_id`!=0";
	$urlbase="?action=comment&do=blog";
}
elseif($view=='mp3'){
    //��������
	$CommentTotal=$DB->getresultnum("SELECT count(comment_id) FROM `".DB_PREFIX."comment` where `mp3_id`!='0' $show_con");
	$CommentType_c="where `mp3_id`!=0";
	$urlbase="?action=comment&do=mp3";
}
elseif($view=='photo'){
    //��������
	$CommentTotal=$DB->getresultnum("SELECT count(comment_id) FROM `".DB_PREFIX."comment` where `file_id`!='0' $show_con");
	$CommentType_c="where `file_id`!=0";
	$urlbase="?action=comment&do=photo";
}
elseif($view=='taotao'){
    //΢������
	$CommentTotal=$DB->getresultnum("SELECT count(comment_id) FROM `".DB_PREFIX."comment` where `taotao_id`!='0' $show_con");
	$CommentType_c="where `taotao_id`!=0";
	$urlbase="?action=comment&do=taotao";
}elseif($view=='guestbook'){
    //���԰�����
	$CommentTotal=$DB->getresultnum("SELECT count(comment_id) FROM `".DB_PREFIX."comment` where `taotao_id`='0' and `blog_id`='0' and `mp3_id`='0' and `file_id`='0' $show_con");
	$CommentType_c="where `taotao_id`=0 and `blog_id`=0 and `mp3_id`=0 and `file_id`=0";
	$urlbase="?action=comment&do=guestbook";
}else{
    //��������
	$CommentTotal=$DB->getresultnum("SELECT count(comment_id) FROM `".DB_PREFIX."comment` $show_con");
	$CommentType_c="";
	$urlbase="?action=comment&do=list";
}

$the_url=$urlbase.$the_url;

if($CommentTotal){
   Page($CommentTotal,$dyhb_options[admin_log_num]);
   $AdminComment=$DB->gettworow("select *from ".DB_PREFIX."comment  $CommentType_c $show_con $Sql limit $pagestart,$dyhb_options[admin_log_num]");
}

//���۲���
if($view=="prepare"){
	$comments_array=isset($_POST['comments'])?$_POST['comments']:'';
	$commentact= isset($_POST['prepare']) ? $_POST['prepare'] : '';
	if($commentact=="del"){
        $_Comments->ActionComment($comments_array,'del');
		CacheNewComment();
		CacheNewGuestbook();
		//���¾�̬��
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("guestbook");
	  }
		header("Location:?action=comment{$commenttype_c}&del=true");
	}
	elseif($commentact=="hide"){
        $_Comments->ActionComment($comments_array,'hide');
		CacheNewComment();
		CacheNewGuestbook();
		//���¾�̬��
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("guestbook");
	  }
		header("Location:?action=comment{$commenttype_c}&hide=true");
	}
	elseif($commentact=="show"){
        $_Comments->ActionComment($comments_array,'show');
		CacheNewComment();
		CacheNewGuestbook();
		//���¾�̬��
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("guestbook");
	  }
		header("Location:?action=comment{$commenttype_c}&show=true");
	}
	elseif($commentact=="tomessage"){
        $_Comments->ActionComment($comments_array,'tomessage');
		CacheNewComment();
		CacheNewGuestbook();
		//���¾�̬��
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("guestbook");
	  }
		header("Location:?action=comment{$commenttype_c}&tomessage=true");
	}
}
//ɾ������
if($view=="del"&&$CommentId){
	//ɾ��ǰ�������������۵ĸ���ID��Ϊ���ĸ�ID
	$the_comment=$_Comments->GetOneComment( $CommentId );
	$the_child=$_Comments->GetChildComment( $CommentId );
	if( $the_child!=false ){
	     foreach( $the_child as $value ){
		     $DB->query( "update `".DB_PREFIX."comment` set parentcomment_id=$the_comment[parentcomment_id] where comment_id=$value[comment_id]" );
		 }
	}
	//���ɾ��
	$_Comments->DeleteComment( $CommentId );
	CacheNewComment();
	CacheNewGuestbook();
	//���¾�̬��
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("guestbook");
	  }
    header("Location:?action=comment{$commenttype_c}&delOne=true");
}

//��������
if($view=='rebuildcomment'){
	$the_comment=$DB->gettworow("select *from `".DB_PREFIX."comment`");
	if($the_comment){
    foreach($the_comment as $value){
	if($value['parentcomment_id']!='0'){
		$result=$_Comments->GetOneComment($value['parentcomment_id']);
		if(!$result){
			$DB->query("update `".DB_PREFIX."comment` set `parentcomment_id`=0 where `comment_id`=$value[comment_id]");
		}
	}}}
	CacheNewComment();
	CacheNewGuestbook();
	//���¾�̬��
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("guestbook");
	  }
	header("Location:?action=comment{$commenttype_c}&rebulidcomment=true");
}

//��������
if($view=="hide"&&$CommentId){
	$_Comments->HideShowComment(0,$CommentId);
	CacheNewComment();
	CacheNewGuestbook();
	//���¾�̬��
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("guestbook");
	  }
    header("Location:?action=comment{$commenttype_c}&hideOne=true");
}
//�ظ�����
if($view=='reply'&&$CommentId){
   if(!$_POST[ok]){
      $UpdComment=$_Comments->GetOneComment($CommentId);
   }else{
	  //������
      $name=sql_check( get_argpost('name'));
	  $url=sql_check( get_argpost('url'));
	  $email=sql_check( get_argpost('email'));
	  $comment= get_argpost('comment');
	  //��ʽ���
	  if($name==''){
	     DyhbMessage($common_func[261],'');
	  }
	  if($comment==''){
	     DyhbMessage($common_func[262],'');
	  }
	  if($url){
	     isurl($url);
	  }
	  if($email){
	     dyhb_email($email);
	  }
	  $SaveCommentDate=array(
	    'name'=>addslashes($name),
		'url'=>$url,
		'email'=>$email,
		'comment'=>addslashes($comment),
		);
	   $_Comments->UpdateComment($SaveCommentDate,$CommentId);
	   CacheNewComment();
	   CacheNewGuestbook();
	   //���¾�̬��
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("guestbook");
	  }
	   header("Location:?action=comment&do=reply&id=$CommentId&reply=true");
   }
}
include DyhbView('comment',1);

?>