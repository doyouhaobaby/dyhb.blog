<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：c.function.mp3.php
        * 说明：音乐
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

class Mp3s{
 //$db
 public $DB;

 function __construct($newdb){
	$this->DB=$newdb;
 }

/**
 * 增加音乐分类
 *
 */
function AddMp3Sort($SaveMp3sortDate){
  add_sql($SaveMp3sortDate,'mp3sort');
}

/**
 * 更新音乐分类
 *
 */
function UpdMp3Sort($SaveMp3SortDate,$mp3sort_id){
  update_sql($SaveMp3SortDate,'mp3sort_id',$mp3sort_id,'mp3sort');
}
	   
/**
 * 删除音乐分类
 *
 */
function DeleteMp3Sort($mp3sort_id){     
  $this->DB->query("DELETE FROM `".DB_PREFIX."mp3sort` where `mp3sort_id`=$mp3sort_id ");
  //移动音乐至默认分类
  $this->DB->query("update `".DB_PREFIX."mp3` set `mp3sort_id`='-1' where `mp3sort_id`='$mp3sort_id'");
}

/**
 * 音乐分类
 *
 */
function GetMp3Sorts($mp3sort_id=''){    
  if($mp3sort_id==''){
	 $mp3sorts =$this->DB->gettworow("select *from `".DB_PREFIX."mp3sort`  order by `compositor` asc");
	 if($mp3sorts){
	 $i=0;
	 foreach($mp3sorts as $value){
      $mp3sorts[$i]['mp3sortname']=stripslashes($value['mp3sortname']);
	  $i++;
    }}
  }else{ 
      $mp3sorts =$this->DB->getonerow("select *from `".DB_PREFIX."mp3sort` where `mp3sort_id`=$mp3sort_id");
	  $mp3sorts['mp3sortname']=stripslashes($mp3sorts['mp3sortname']);
  }	  
  return $mp3sorts;
}
		 
/**
 * 增加mp3
 *
 */
function AddMp3($SaveMp3Date){
   add_sql($SaveMp3Date,'mp3');
}
	 
/**
 * 更新音乐衔接
 *
 */
function UpdMp3($SaveMp3Date,$mp3_id){
  update_sql($SaveMp3Date,'mp3_id',$mp3_id,'mp3');
}

/**
 * 删除音乐
 *
 */
function DelMp3($mp3_id){      
	$this->DB->query("DELETE FROM `".DB_PREFIX."mp3` where `mp3_id`=$mp3_id ");
	//移动评论，将评论转为留言板内容
	$this->DB->query("update `".DB_PREFIX."comment` set `mp3_id`='0' where `mp3_id`='$mp3_id'");
}

 /**
  * 移动音乐
  *
  */
function ChangeMp3Sort($mp3_id,$mp3sort_id){
	$this->DB->query("update `".DB_PREFIX."mp3` set `mp3sort_id`=$mp3sort_id where `mp3_id`=$mp3_id");
}

/**
 * 获取单个音乐
 *
 */
function GetOneMp3($mp3_id){
	$idmp3=$this->DB->getonerow("select *from`".DB_PREFIX."mp3` where `mp3_id`=$mp3_id");
	if($idmp3){
	    $idmp3['mp3name']=stripslashes($idmp3['mp3name']);
	}
	return $idmp3;
}

/**
  * 所有音乐
  *
  */
 function GetMp3s($Mp3sort_id,$start,$end){   
	$Mp3sort=$Mp3sort_id?"where `mp3sort_id`='$Mp3sort_id'":'';
	$limited=($start==''&&$end=='')?'':"limit $start,$end";
	$Mp3s=$this->DB->gettworow("select *from `".DB_PREFIX."mp3` $Mp3sort order by `dateline` desc $limited");
	if($Mp3s){
	$i=0;
	foreach($Mp3s as $value){
     $Mp3s[$i]['name']=stripslashes($value['name']);
	 $i++;
   }}
	return $Mp3s;
  }
}

?>