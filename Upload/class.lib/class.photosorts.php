<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：c.function.photosorts.php
        * 说明：相册
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

class Photosorts{
  public $DB;

  function __construct($newdb){
	$this->DB=$newdb;
  }

 /**
  * 增加相册分类
  *
  */
function AddSort($SavePhotosortDate){
   add_sql($SavePhotosortDate,'photosort');
}

/**
 * 更新相册分类
 *
 */
function UpdSort($SaveSortDate,$photosort_id){
 update_sql($SaveSortDate,'photosort_id',$photosort_id,'photosort');
}
	   
/**
 * 删除相册分类
 *
 */
function DelSort($photosort_id){     
  $this->DB->query("DELETE FROM `".DB_PREFIX."photosort` where `photosort_id`=$photosort_id ");
  //移动图片至默认相册
  $this->DB->query("update `".DB_PREFIX."file` set `photosort_id`='-1' where `photosort_id`='$photosort_id'");
}

/**
 * 相册分类
 *
 */
function GetSorts($photosort_id=''){    
 if($photosort_id==''){
	$sorts =$this->DB->gettworow("select *from `".DB_PREFIX."photosort`  order by compositor asc");
	if($sorts){
	$i=0;
	foreach($sorts as $value){
    $sorts[$i]['name']=stripslashes($value['name']);
	$i++;
   }}
}else{ 
    $sorts =$this->DB->getonerow("select *from `".DB_PREFIX."photosort` where `photosort_id`=$photosort_id");
    $sorts['name']=stripslashes($sorts['name']);
} 
 
 return $sorts;
}
		 
/**
 * 增加附件
 *
 */
function AddFile($SaveFileDate){
 add_sql($SaveFileDate,'file');
 $file_id=$this->DB->insert_id();
 return $file_id;
}

/**
 * 更新附件
 *
 */
function UpdFile($FileDate,$file_id){
 update_sql($FileDate,'file_id',$file_id,'file');
}

/**
 * 删除附件
 *
 */
function DelFile($file_id){      
  $this->DB->query("DELETE FROM ".DB_PREFIX."file where file_id=$file_id ");
  //移动评论，将评论转为留言板内容
  $this->DB->query("update `".DB_PREFIX."comment` set `file_id`='0' where `file_id`='$file_id'");
}

/**
 * 移动附件
 *
 */
function ChangeFileSort($file_id,$photosort_id){
	$this->DB->query("update `".DB_PREFIX."file` set `photosort_id`=$photosort_id where `file_id`=$file_id");
}

/**
 * 获取单个文件
 *
 */
function GetIdFile($file_id){
  $idfile=$this->DB->getonerow("select *from`".DB_PREFIX."file` where file_id=$file_id");
  if($idfile){
      $idfile['filename']=stripslashes($idfile['name']);
  }
  return $idfile;
}
		
/**
  * 相邻相片
  *
  */
function GetPreFile($file_id){
  $prefile=$this->DB->getonerow("SELECT  *FROM `".DB_PREFIX."file` WHERE file_id>$file_id and `name` REGEXP '(jpg|jpeg|bmp|gif|png)$' ORDER BY `dateline` DESC LIMIT 1");
  if($prefile){
      $prefile['filename']=stripslashes($prefile['filename']);
  }
  return $prefile;
}

function GetNextFile($file_id){
 $nextfile=$this->DB->getonerow("SELECT *FROM `".DB_PREFIX."file` WHERE file_id<$file_id and `name` REGEXP '(jpg|jpeg|bmp|gif|png)$' ORDER BY `dateline` DESC LIMIT 1");
 if($nextfile){
     $nextfile['name']=stripslashes($nextfile['name']);
 }
 return $nextfile;
}

/**
 * 所有附件
 *
 */
function GetFiles($photosort_id,$start,$end,$conditon=''){   
  $photosort=$photosort_id==''?'':"where `photosort_id`='$photosort_id'";
  $conditon=$conditon==''?'':$conditon;
  $limited=($start==''&&$end=='')?'':"limit $start,$end";
  $Sql="select *from `".DB_PREFIX."file` $conditon $photosort order by `dateline` desc $limited";
  $Files =$this->DB->gettworow($Sql);
  if($Files){
  $i=0;
  foreach($Files as $value){
    $Files[$i]['name']=stripslashes($value['name']);
	$i++;
  }
  }
  return $Files;
}

}
?>