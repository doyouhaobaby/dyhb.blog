<?php
/**================[^_^]================**\
       ---- 因为有梦，所以有目标 ----
@----------------------------------------@
        * 文件：c.function.logs.php
        * 说明：日志封装
        * 作者：小牛哥
        * 时间：2010-02-15 16:04
        * 版本：DoYouHaoBaby-blog version1.1
        * 主页：www.doyouhaobaby.com
@----------------------------------------@
 ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

 class Tags
  {
	public $DB;
	function __construct($newdb){
		$this->DB=$newdb;
	}

/**
 * 添加标签
 *
 */
function AddTag($tagStr,$url,$blog_id,$keyword,$description){
//标签分割
$tag = !empty($tagStr) ? explode('，', $tagStr) : array();
$tag = array_unique($tag);
$url=$url?$url:'';
$url_c=$url?",`urlname`='$url'":'';
$url_c.=$keyword?",`keyword`='$keyword'":'';
$url_c.=$description?",`description`='$description'":'';
foreach ($tag as $tagName){
$result = $this->DB->getonerow("SELECT `name` FROM ".DB_PREFIX."tag WHERE `name`='$tagName'");
$blog_c='';
if(empty($result)) {
	$blog_c.=$blog_id?",$blog_id,":'';
	$query="INSERT INTO ".DB_PREFIX."tag (`name`,`urlname`,`blog_id`,`keyword`,`description`) VALUES('".$tagName."','$url','$blog_c','$keyword','$description')";
	$this->DB->query($query);
}else{
	$blog_c.=$blog_id?"$blog_id,":'';
	$query="UPDATE ".DB_PREFIX."tag SET blog_id=concat(blog_id,'$blog_c') $url_c where `name` = '$tagName'";
	$this->DB->query($query);
   }
  }
}

/**
 * 更新标签
 *
 */
function UpdTag($tagStr, $blog_id,$urlname){
$tag = !empty($tagStr) ? explode('，', $tagStr) : array();
$tags= $this->DB->gettworow("SELECT `name` FROM ".DB_PREFIX."tag WHERE blog_id LIKE '%".$blog_id."%' ");
$old_tag = array();
foreach($tags as $value){
  $old_tag[] = $value['name'];
}
if(empty($old_tag)){
  $old_tag = array('');
}
$dif_tag =findArray(array_unique($tag),$old_tag);
for($n = 0; $n < count($dif_tag); $n++){
  $a = 0;
  for($j=0 ; $j<count($old_tag);$j++){
  if($dif_tag[$n] == $old_tag[$j]){
  $this->DB->query("UPDATE ".DB_PREFIX."tag SET blog_id= REPLACE(blog_id,',$blog_id,',',') WHERE `name`='".$dif_tag[$n]."' ");
  $this->DB->query("DELETE FROM ".DB_PREFIX."tag WHERE blog_id=',' ");
  break;
}elseif($j == count($old_tag)-1){
  $result = $this->DB->getonerow("SELECT `name` FROM ".DB_PREFIX."tag WHERE `name`='".trim($dif_tag[$n])."' ");
  if(empty($result)){
	$query="INSERT INTO ".DB_PREFIX."tag (name,blog_id) VALUES('".$dif_tag[$n]."',',$blog_id,')";
	$this->DB->query($query);
	}else{
	$query="UPDATE ".DB_PREFIX."tag SET blog_id=concat(blog_id,'$blog_id,') where `name` = '".$dif_tag[$n]."' ";
	$this->DB->query($query);
	}
   }
  }
 }
}

/**
 * 删除标签
 *
 */
function DelTag($tag_id){
  $this->DB->query("DELETE FROM `".DB_PREFIX."tag` where `tag_id`=$tag_id");
}

/**
 * tag_id获取标签信息
 *
 */
function GetBlog_idStr($tag_id){
$tagStr= $this->DB->getonerow("SELECT blog_id FROM ".DB_PREFIX."tag WHERE tag_id=$tag_id");
if(empty($tagStr)){
  return false;
}
$blog_idStr  = substr(trim($tagStr['blog_id']),1,-1);
 return $blog_idStr;
}

/**
 * tagname获取标签信息
 *
 */
function GetBlog_idStrByName($tagname){
$tagStr= $this->DB->getonerow("SELECT `blog_id` FROM `".DB_PREFIX."tag` WHERE `name`='$tagname' OR `urlname`='$tagname'");
if(empty($tagStr)){
  return false;
}
$blog_idStr  = substr(trim($tagStr['blog_id']),1,-1);
return $blog_idStr;
}

/**
 * 获取单个标签
 *
 */
function GetOneTag($tag_id){
$onetag=$this->DB->getonerow("select *from `".DB_PREFIX."tag` where `tag_id`=$tag_id");
if($onetag){
	$onetag['name']=stripslashes($onetag['name']);
}
return $onetag;
}

function GetOneTagByName($tag_name){
$onetag=$this->DB->getonerow("select *from `".DB_PREFIX."tag` where `name`='$tag_name' or `urlname`='$tag_name'");
if($onetag){
	$onetag['name']=stripslashes($onetag['name']);
}
return $onetag;
}

/**
 * 更新单个标签
 *
 */
function UpdTagName($tag_id,$tagname,$urlname,$keyword,$description){
  $this->DB->query("UPDATE `".DB_PREFIX."tag` SET `name` = '$tagname',`urlname`='$urlname' ,`keyword`='$keyword' ,`description`='$description' WHERE `tag_id` ='$tag_id'");
}

/**
 * 标签信息
 *
 */
function GetTag($blog_id='',$start,$end) {
		$limited=($start==0&&$end==0)?'':"limit $start,$end";
		$condition = $blog_id ? "WHERE blog_id LIKE '%,$blog_id,%'" : '';
		$tags= $this->DB->gettworow("select *from ".DB_PREFIX."tag $condition order by `tag_id` desc $limited");
		return $tags;
	 }
}

?>