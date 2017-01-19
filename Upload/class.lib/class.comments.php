<?php
/**================[^_^]================**\
       ---- 因为有梦，所以有目标 ----
@----------------------------------------@
        * 文件：c.function.comments.php
        * 说明：评论封装
        * 作者：小牛哥
        * 时间：2010-05-06 20:22
        * 版本：DoYouHaoBaby-blog 概念版
        * 主页：www.doyouhaobaby.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

class Comments{
public $DB;

function __construct($newdb){
	$this->DB=$newdb;
}

/**
 * 增加评论
 *
 */
function AddComments($CommentDate){
  global $dyhb_options;
  add_sql($CommentDate,'comment');
  $comment_id=$this->DB->insert_id();
  if($dyhb_options[com_examine]=='0'&&$CommentDate['blog_id']){
     $this->DB->query("update `".DB_PREFIX."blog` set commentnum=commentnum+1 where `blog_id`=".$CommentDate['blog_id']."");
  }
  //这里判断是留言，还是评论，0表示留言
  if($CommentDate['blog_id']=='0'){
	  return $this->DB->gettworow("select *from `".DB_PREFIX."comment` where `comment_id`='$comment_id'");
  }else{
	  return $this->GetComment("order by `comment_id` desc",$CommentDate['blog_id'],'','0','1',$CommentDate['taotao_id'],$CommentDate['photo_id'],$CommentDate['file_id']);
  }
}

/**
 * 更新评论
 */
function UpdateComment($SaveCommentDate,$comment_id){
	update_sql($SaveCommentDate,'comment_id',$comment_id,'comment');
}

/**
 * 操作评论
 *
 */
function DeleteComment($comment_id){
   $r=$this->DB->getonerow("select *from `".DB_PREFIX."comment` where `comment_id`='$comment_id'");
   if($r[blog_id]!=0){
       $this->DB->query("update `".DB_PREFIX."blog` set `commentnum`=commentnum-1 where `blog_id`='$r[blog_id]'");
   }
   $this->DB->query("delete from `".DB_PREFIX."comment` where `comment_id`=$comment_id");
}

function HideShowComment($isshow,$comment_id){
   $this->DB->query("update  `".DB_PREFIX."comment` set `isshow`=$isshow where `comment_id`=$comment_id");
   $r=$this->DB->getonerow("select `blog_id` from `".DB_PREFIX."comment` where `comment_id`='$comment_id'");
   if($r[blog_id]!=0){
       $this->DB->query("update `".DB_PREFIX."blog` set commentnum=commentnum+1 where `blog_id`=".$r['blog_id']."");
   }
}

function ToMessage($comment_id){
   $this->DB->query("update  `".DB_PREFIX."comment` set `blog_id`=0,`mp3_id`=0,`taotao_id`=0,`file_id`=0 where `comment_id`=$comment_id");
   $r=$this->DB->getonerow("select `blog_id` from `".DB_PREFIX."comment` where `comment_id`='$comment_id'");
   if($r[blog_id]!=0){
       $this->DB->query("update `".DB_PREFIX."blog` set commentnum=commentnum-1 where `blog_id`=".$r['blog_id']."");
   }
}

function ActionComment($comment_ids,$action){
  switch($action){
	case 'del';
	foreach($comment_ids as $value){
	  $this->DeleteComment($value);
	}
   break;
   case 'show';
   foreach($comment_ids as $value){
		$this->HideShowComment(1,$value);
   }
   break;
   case 'hide';
   foreach($comment_ids as $value){
		$this->HideShowComment(0,$value);
   }
   break;
   case 'tomessage';
   foreach($comment_ids as $value){
	 $this->ToMessage($value);
	 $r=$this->DB->getonerow("select `blog_id` from `".DB_PREFIX."comment` where `comment_id`='$value'");
	 if($r[blog_id]!='0'){
	     $this->DB->query("update `".DB_PREFIX."blog` set commentnum=commentnum-1 where `blog_id`=".$r['blog_id']."");
	 }
   }
   break;
 }
}

/**
 * 特定日志的评论*留言板*后台*前台
 * boolean $isAdminComment 是否为后台
 *
 */
 function GetComment($condition,$blog_id,$isshow,$start,$end,$taotao_id='0',$photo_id='0',$mp3_id='0'){
	$limited=($start==''&&$end=='')?"":"limit $start,$end";
	$show1=$isshow!=''?"and `isshow`='$isshow'":'';
	//这里可以通过查询日志表和评论表来获取数据，但是本站暂时没有用，所以放在这里
	//$show2=$isshow!=''?"and a.isshow='$isshow'":'';
	$photo_c=$photo_id?"`file_id`='$photo_id'":'';
	$taotao_c=$taotao_id?"`taotao_id`='$taotao_id'":'';
	$mp3_c=$mp3_id?"`mp3_id`='$mp3_id'":'';

	/** 隐藏日志权限验证 */
    if(CheckPermission("seehiddencomment","这是一篇隐藏评论！",'0')&&!$isAdminComment){
   	   $show1="";
	   $show2="";
    }
	if($blog_id!=""){
	if($blog_id!=0){
        $blogcomment=$this->DB->gettworow("select *from `".DB_PREFIX."comment` where `blog_id`='$blog_id'  $show1 $condition $limited");
     }else{ 
		  if($photo_id){
		     //获取相册评论
             $blogcomment=$this->DB->gettworow("select *from `".DB_PREFIX."comment` where  $photo_c $show1 $condition $limited ");
		  }
		  elseif($taotao_id){
		     //获取滔滔心情评论
			 $blogcomment=$this->DB->gettworow("select *from `".DB_PREFIX."comment` where  $taotao_c $show1 $condition $limited ");
		  }
		  elseif($mp3_id){
		     //获取音乐评论
			 $blogcomment=$this->DB->gettworow("select *from `".DB_PREFIX."comment` where $mp3_c $show1 $condition $limited ");
		  }
		  else{
		      //获取留言
	          $blogcomment=$this->DB->gettworow("select *from `".DB_PREFIX."comment` where `blog_id`=0 and `file_id`='0' and `taotao_id`='0' and `mp3_id`='0' $show1 $condition $limited ");
		  }
	 }
   }else{ //获取全部评论，不是留言板内容
         $blogcomment=$this->DB->gettworow("select *from `".DB_PREFIX."comment` where `blog_id`!='0'  $show1 $condition $limited");
	}
	if($blogcomment){
	$i=0;
	foreach($blogcomment as $value){
		$blogcomment[$i]['name']=stripslashes($value['name']);
		$blogcomment[$i]['name']=$value[ismobile]=='1'?this_is_mobile($blogcomment[$i]['name'],'1'):$blogcomment[$i]['name'];
		$blogcomment[$i]['comment']=stripslashes($value['comment']);
		$i++;
	}}
	return $blogcomment;
}

/**
 * 获取某评论的子评论
 *
 */
function GetChildComment($parentcomment_id){
   $Comment=$this->DB->gettworow("select * from `".DB_PREFIX."comment` where `parentcomment_id`=$parentcomment_id");
   if( !$Comment ) return false;
   return $Comment;
 }

/**
 * 获取单条评论
 *
 */
function GetOneComment($comment_id){
   $OneComment=$this->DB->getonerow("select * from `".DB_PREFIX."comment` where `comment_id`=$comment_id");
   if($OneComment){
     $OneComment['name']=stripslashes($OneComment['name']);
     $OneComment['comment']=stripslashes($OneComment['comment']);
   }
   return $OneComment;
 }
}
?>