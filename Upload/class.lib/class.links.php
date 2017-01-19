<?php
/**================[^_^]================**\
        ---- Not TM,But TMD ! ----
@----------------------------------------@
        * 文件：c.function.links.php
        * 说明：友情衔接封装
        * 作者：小牛哥
        * 时间：2010-07-22 0:17
        * 版本：DoYouHaoBaby-blog 告别大二.想念丝瓜 (特别版)
        * 主页：www.doyouhaobaby.com
		* 论坛：bbs.56swun.com
@----------------------------------------@
  ---- 西南民大的天空还是蛮明亮的哈 ----
\**=====copyright (c) DoYouHaoBaby=====**/

class Links{
public $DB;

function __construct($newdb){
  $this->DB=$newdb;
}

/**
 * 添加衔接
 *
 */
function AddLink($SaveLinkDate){
 add_sql($SaveLinkDate,'link');
}

/**
 * 更新友情衔接
 *
 */
function UpdLink($SaveLinkDate,$link_id){
 update_sql($SaveLinkDate,'link_id',$link_id,'link');
}

/**
 * 删除友情衔接
 *
 */
function DeleteLink($link_id){
	$this->DB->query("delete from `".DB_PREFIX."link` where `link_id`='$link_id'");
}

/**
 * 单个友情衔接
 *
 */
function GetOneLink($link_id){
  $onelink=$this->DB->getonerow("select *from `".DB_PREFIX."link` where link_id=$link_id");
  if($onelink){
      $onelink['name']=stripslashes($onelink['name']);
      $onelink['description']=stripslashes($onelink['description']);
  }
  return $onelink;
}

/**
 * 友情衔接
 *
 */
function GetLinks(){
  $a1=$this->DB->gettworow("select *from `".DB_PREFIX."link` where `logo`='' order by `compositor` asc");
  $a2=$this->DB->gettworow("select *from `".DB_PREFIX."link` where `logo`!='' order by `compositor` asc");
  if($a1){
  $i=0;
  foreach($a1 as $value){
    $a1[$i]['name']=stripslashes($value['name']);
	$a1[$i]['description']=stripslashes($value['description']);
	$i++;
  }}
  if($a2){
  $i=0;
  foreach($a2 as $value){
    $a2[$i]['name']=stripslashes($value['name']);
	$a2[$i]['description']=stripslashes($value['description']);
	$i++;
  }}
  return array($a1,$a2);
}
}

?>