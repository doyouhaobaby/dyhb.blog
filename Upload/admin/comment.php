<?php
/**================[^_^]================**\
        ---- 今天天气冷嘻嘻的 ----
@----------------------------------------@
        * 文件：comment.php
        * 说明：评论管理
        * 作者：小牛哥
        * 时间：2010-05-06 20:22
        * 版本：DoYouHaoBaby-blog 概念版
        * 主页：www.doyouhaobaby.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

CheckPermission("cp",$common_permission[43]);

//评论列表参数
$CommentId = intval( get_argget('id'));

//排序
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

//是否为显示的日志
if($view2=='show'){
	$isshow='1';
}
elseif($view2=='hidden'){
	$isshow='0';
}else{
    $isshow='';
}

//显示条件构造
if($isshow!=''){
   if($view=='blog'||$view=='mp3'||$view=='photo'||$view=='taotao'||$view=='guestbook'){
        $show_con="and `isshow`=$isshow";
   }else{
        $show_con="where `isshow`=$isshow";
   }
}else{
    $show_con='';
}

/** 评论总数 */
if($view=='blog'){
    //日志评论
	$CommentTotal=$DB->getresultnum("SELECT count(comment_id) FROM `".DB_PREFIX."comment` where `blog_id`!='0' $show_con");
	$CommentType_c="where `blog_id`!=0";
	$urlbase="?action=comment&do=blog";
}
elseif($view=='mp3'){
    //音乐评论
	$CommentTotal=$DB->getresultnum("SELECT count(comment_id) FROM `".DB_PREFIX."comment` where `mp3_id`!='0' $show_con");
	$CommentType_c="where `mp3_id`!=0";
	$urlbase="?action=comment&do=mp3";
}
elseif($view=='photo'){
    //音乐评论
	$CommentTotal=$DB->getresultnum("SELECT count(comment_id) FROM `".DB_PREFIX."comment` where `file_id`!='0' $show_con");
	$CommentType_c="where `file_id`!=0";
	$urlbase="?action=comment&do=photo";
}
elseif($view=='taotao'){
    //微博评论
	$CommentTotal=$DB->getresultnum("SELECT count(comment_id) FROM `".DB_PREFIX."comment` where `taotao_id`!='0' $show_con");
	$CommentType_c="where `taotao_id`!=0";
	$urlbase="?action=comment&do=taotao";
}elseif($view=='guestbook'){
    //留言板评论
	$CommentTotal=$DB->getresultnum("SELECT count(comment_id) FROM `".DB_PREFIX."comment` where `taotao_id`='0' and `blog_id`='0' and `mp3_id`='0' and `file_id`='0' $show_con");
	$CommentType_c="where `taotao_id`=0 and `blog_id`=0 and `mp3_id`=0 and `file_id`=0";
	$urlbase="?action=comment&do=guestbook";
}else{
    //所有评论
	$CommentTotal=$DB->getresultnum("SELECT count(comment_id) FROM `".DB_PREFIX."comment` $show_con");
	$CommentType_c="";
	$urlbase="?action=comment&do=list";
}

$the_url=$urlbase.$the_url;

if($CommentTotal){
   Page($CommentTotal,$dyhb_options[admin_log_num]);
   $AdminComment=$DB->gettworow("select *from ".DB_PREFIX."comment  $CommentType_c $show_con $Sql limit $pagestart,$dyhb_options[admin_log_num]");
}

//评论操作
if($view=="prepare"){
	$comments_array=isset($_POST['comments'])?$_POST['comments']:'';
	$commentact= isset($_POST['prepare']) ? $_POST['prepare'] : '';
	if($commentact=="del"){
        $_Comments->ActionComment($comments_array,'del');
		CacheNewComment();
		CacheNewGuestbook();
		//更新静态化
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("guestbook");
	  }
		header("Location:?action=comment{$commenttype_c}&del=true");
	}
	elseif($commentact=="hide"){
        $_Comments->ActionComment($comments_array,'hide');
		CacheNewComment();
		CacheNewGuestbook();
		//更新静态化
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("guestbook");
	  }
		header("Location:?action=comment{$commenttype_c}&hide=true");
	}
	elseif($commentact=="show"){
        $_Comments->ActionComment($comments_array,'show');
		CacheNewComment();
		CacheNewGuestbook();
		//更新静态化
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("guestbook");
	  }
		header("Location:?action=comment{$commenttype_c}&show=true");
	}
	elseif($commentact=="tomessage"){
        $_Comments->ActionComment($comments_array,'tomessage');
		CacheNewComment();
		CacheNewGuestbook();
		//更新静态化
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("guestbook");
	  }
		header("Location:?action=comment{$commenttype_c}&tomessage=true");
	}
}
//删除评论
if($view=="del"&&$CommentId){
	//删除前处理，把其子评论的父亲ID改为它的父ID
	$the_comment=$_Comments->GetOneComment( $CommentId );
	$the_child=$_Comments->GetChildComment( $CommentId );
	if( $the_child!=false ){
	     foreach( $the_child as $value ){
		     $DB->query( "update `".DB_PREFIX."comment` set parentcomment_id=$the_comment[parentcomment_id] where comment_id=$value[comment_id]" );
		 }
	}
	//最后删除
	$_Comments->DeleteComment( $CommentId );
	CacheNewComment();
	CacheNewGuestbook();
	//更新静态化
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("guestbook");
	  }
    header("Location:?action=comment{$commenttype_c}&delOne=true");
}

//评论整理
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
	//更新静态化
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("guestbook");
	  }
	header("Location:?action=comment{$commenttype_c}&rebulidcomment=true");
}

//隐藏评论
if($view=="hide"&&$CommentId){
	$_Comments->HideShowComment(0,$CommentId);
	CacheNewComment();
	CacheNewGuestbook();
	//更新静态化
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("guestbook");
	  }
    header("Location:?action=comment{$commenttype_c}&hideOne=true");
}
//回复评论
if($view=='reply'&&$CommentId){
   if(!$_POST[ok]){
      $UpdComment=$_Comments->GetOneComment($CommentId);
   }else{
	  //表单数据
      $name=sql_check( get_argpost('name'));
	  $url=sql_check( get_argpost('url'));
	  $email=sql_check( get_argpost('email'));
	  $comment= get_argpost('comment');
	  //格式检查
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
	   //更新静态化
	  if( $dyhb_options[allowed_make_html]=='1'){
              MakePagenav("guestbook");
	  }
	   header("Location:?action=comment&do=reply&id=$CommentId&reply=true");
   }
}
include DyhbView('comment',1);

?>