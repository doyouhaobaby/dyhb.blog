<?php
/**================[^_^]================**\
       ---- ��Ϊ���Σ�������Ŀ�� ----
@----------------------------------------@
        * �ļ���c.function.comments.php
        * ˵�������۷�װ
        * ���ߣ�Сţ��
        * ʱ�䣺2010-05-06 20:22
        * �汾��DoYouHaoBaby-blog �����
        * ��ҳ��www.doyouhaobaby.com
@----------------------------------------@
  ---- ����������ջ����������Ĺ� ----
\**=====copyright (c) DoYouHaoBaby=====**/

class Comments{
public $DB;

function __construct($newdb){
	$this->DB=$newdb;
}

/**
 * ��������
 *
 */
function AddComments($CommentDate){
  global $dyhb_options;
  add_sql($CommentDate,'comment');
  $comment_id=$this->DB->insert_id();
  if($dyhb_options[com_examine]=='0'&&$CommentDate['blog_id']){
     $this->DB->query("update `".DB_PREFIX."blog` set commentnum=commentnum+1 where `blog_id`=".$CommentDate['blog_id']."");
  }
  //�����ж������ԣ��������ۣ�0��ʾ����
  if($CommentDate['blog_id']=='0'){
	  return $this->DB->gettworow("select *from `".DB_PREFIX."comment` where `comment_id`='$comment_id'");
  }else{
	  return $this->GetComment("order by `comment_id` desc",$CommentDate['blog_id'],'','0','1',$CommentDate['taotao_id'],$CommentDate['photo_id'],$CommentDate['file_id']);
  }
}

/**
 * ��������
 */
function UpdateComment($SaveCommentDate,$comment_id){
	update_sql($SaveCommentDate,'comment_id',$comment_id,'comment');
}

/**
 * ��������
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
 * �ض���־������*���԰�*��̨*ǰ̨
 * boolean $isAdminComment �Ƿ�Ϊ��̨
 *
 */
 function GetComment($condition,$blog_id,$isshow,$start,$end,$taotao_id='0',$photo_id='0',$mp3_id='0'){
	$limited=($start==''&&$end=='')?"":"limit $start,$end";
	$show1=$isshow!=''?"and `isshow`='$isshow'":'';
	//�������ͨ����ѯ��־������۱�����ȡ���ݣ����Ǳ�վ��ʱû���ã����Է�������
	//$show2=$isshow!=''?"and a.isshow='$isshow'":'';
	$photo_c=$photo_id?"`file_id`='$photo_id'":'';
	$taotao_c=$taotao_id?"`taotao_id`='$taotao_id'":'';
	$mp3_c=$mp3_id?"`mp3_id`='$mp3_id'":'';

	/** ������־Ȩ����֤ */
    if(CheckPermission("seehiddencomment","����һƪ�������ۣ�",'0')&&!$isAdminComment){
   	   $show1="";
	   $show2="";
    }
	if($blog_id!=""){
	if($blog_id!=0){
        $blogcomment=$this->DB->gettworow("select *from `".DB_PREFIX."comment` where `blog_id`='$blog_id'  $show1 $condition $limited");
     }else{ 
		  if($photo_id){
		     //��ȡ�������
             $blogcomment=$this->DB->gettworow("select *from `".DB_PREFIX."comment` where  $photo_c $show1 $condition $limited ");
		  }
		  elseif($taotao_id){
		     //��ȡ������������
			 $blogcomment=$this->DB->gettworow("select *from `".DB_PREFIX."comment` where  $taotao_c $show1 $condition $limited ");
		  }
		  elseif($mp3_id){
		     //��ȡ��������
			 $blogcomment=$this->DB->gettworow("select *from `".DB_PREFIX."comment` where $mp3_c $show1 $condition $limited ");
		  }
		  else{
		      //��ȡ����
	          $blogcomment=$this->DB->gettworow("select *from `".DB_PREFIX."comment` where `blog_id`=0 and `file_id`='0' and `taotao_id`='0' and `mp3_id`='0' $show1 $condition $limited ");
		  }
	 }
   }else{ //��ȡȫ�����ۣ��������԰�����
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
 * ��ȡĳ���۵�������
 *
 */
function GetChildComment($parentcomment_id){
   $Comment=$this->DB->gettworow("select * from `".DB_PREFIX."comment` where `parentcomment_id`=$parentcomment_id");
   if( !$Comment ) return false;
   return $Comment;
 }

/**
 * ��ȡ��������
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